<?php

namespace App\Services;

use App\Models\Booking;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    /**
     * Generate QR Code for a confirmed booking
     */
    public function generateForBooking(Booking $booking): string
    {
        $data = json_encode([
            'booking_id' => $booking->id,
            'kode' => $booking->kode_booking,
            'hash' => hash('sha256', $booking->kode_booking . $booking->id . config('app.key')),
        ]);

        $filename = 'qrcodes/booking-' . $booking->kode_booking . '.svg';

        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($data);

        Storage::disk('public')->put($filename, $qrCode);

        $booking->update(['qr_code_path' => $filename]);

        return $filename;
    }

    /**
     * Validate QR Code data
     */
    public function validateQrCode(string $qrData): ?Booking
    {
        try {
            $data = json_decode($qrData, true);

            if (!$data || !isset($data['booking_id'], $data['kode'], $data['hash'])) {
                return null;
            }

            $expectedHash = hash('sha256', $data['kode'] . $data['booking_id'] . config('app.key'));

            if (!hash_equals($expectedHash, $data['hash'])) {
                return null;
            }

            return Booking::where('id', $data['booking_id'])
                ->where('kode_booking', $data['kode'])
                ->where('status', 'confirmed')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
