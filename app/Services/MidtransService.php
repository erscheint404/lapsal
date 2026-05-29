<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap token for a booking
     */
    public function createSnapToken(Booking $booking): string
    {
        $idempotencyKey = $booking->idempotency_key ?? Str::uuid()->toString();

        $params = [
            'transaction_details' => [
                'order_id' => $booking->midtrans_order_id ?? $booking->kode_booking . '-' . time(),
                'gross_amount' => (int) $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'BOOKING-' . $booking->id,
                    'price' => (int) $booking->lapangan->harga_per_jam,
                    'quantity' => $booking->durasi_jam,
                    'name' => 'Sewa ' . $booking->lapangan->nama . ' (' . $booking->durasi_jam . ' jam)',
                ],
            ],
            'callbacks' => [
                'finish' => route('member.booking.show', $booking->id),
            ],
            'headers' => [
                'X-Idempotency-Key' => $idempotencyKey,
            ],
        ];

        if (empty($booking->idempotency_key)) {
            $booking->update(['idempotency_key' => $idempotencyKey]);
        }

        $snapToken = Snap::getSnapToken($params);

        // Save order ID and snap token
        $booking->update([
            'midtrans_snap_token' => $snapToken,
            'midtrans_order_id' => $params['transaction_details']['order_id'],
        ]);

        return $snapToken;
    }

    /**
     * Handle notification/webhook from Midtrans
     */
    public function handleNotification(array $notification): ?Booking
    {
        $orderId = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return null;
        }

        // Extract kode_booking from order_id (format: BK-XXXXXXXX-timestamp)
        $parts = explode('-', $orderId);
        $kodeBooking = $parts[0] . '-' . $parts[1];

        $booking = Booking::where('kode_booking', $kodeBooking)
            ->orWhere('midtrans_order_id', $orderId)
            ->first();

        if (!$booking) {
            return null;
        }

        // Prevent duplicate processing (idempotency)
        if ($booking->status === 'confirmed' || $booking->status === 'completed') {
            return $booking;
        }

        $bookingService = app(BookingService::class);

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || !$fraudStatus) {
                $bookingService->confirmBooking($booking);
            }
        } elseif ($transactionStatus === 'pending') {
            // Still waiting
            $booking->update(['status' => 'pending_payment']);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $booking->update(['status' => 'expired']);

            // Release slots
            \App\Models\SlotWaktu::where('lapangan_id', $booking->lapangan_id)
                ->where('tanggal', $booking->tanggal)
                ->where('jam_mulai', '>=', $booking->jam_mulai)
                ->where('jam_selesai', '<=', $booking->jam_selesai)
                ->where('status', 'reserved')
                ->update(['status' => 'available']);
        }

        return $booking;
    }
}
