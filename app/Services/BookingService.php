<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Notifikasi;
use App\Models\RiwayatBooking;
use App\Models\SlotWaktu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    protected SlotLockService $slotLockService;

    public function __construct(SlotLockService $slotLockService)
    {
        $this->slotLockService = $slotLockService;
    }

    /**
     * Create a new booking from selected slots
     */
    public function createBooking(int $userId, int $lapanganId, string $tanggal, array $slotIds, string $metodePembayaran = 'midtrans'): Booking
    {
        return DB::transaction(function () use ($userId, $lapanganId, $tanggal, $slotIds, $metodePembayaran) {
            $lapangan = Lapangan::findOrFail($lapanganId);
            $slots = SlotWaktu::whereIn('id', $slotIds)
                ->where('lapangan_id', $lapanganId)
                ->where('tanggal', $tanggal)
                ->orderBy('jam_mulai')
                ->lockForUpdate()
                ->get();

            // Validate all slots are available or reserved by this user
            foreach ($slots as $slot) {
                if ($slot->status === 'reserved') {
                    $activeLock = $slot->activeLock;
                    if (!$activeLock || $activeLock->user_id !== $userId) {
                        throw new \Exception("Slot {$slot->waktu} sedang dipesan orang lain.");
                    }
                } elseif ($slot->status !== 'available') {
                    throw new \Exception("Slot {$slot->waktu} tidak tersedia.");
                }
            }

            $jamMulai = $slots->first()->jam_mulai;
            $jamSelesai = $slots->last()->jam_selesai;
            $durasiJam = $slots->count();
            $totalHarga = $lapangan->harga_per_jam * $durasiJam;

            $booking = Booking::create([
                'kode_booking' => 'BK-' . strtoupper(Str::random(8)),
                'user_id' => $userId,
                'lapangan_id' => $lapanganId,
                'tanggal' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'durasi_jam' => $durasiJam,
                'total_harga' => $totalHarga,
                'status' => 'pending_payment',
                'metode_pembayaran' => $metodePembayaran,
                'idempotency_key' => Str::uuid()->toString(),
            ]);

            // Update slot status to booked temporarily (will finalize on payment)
            foreach ($slots as $slot) {
                $slot->update(['status' => 'reserved']);
            }

            // Log riwayat
            $this->logRiwayat($booking, null, 'pending_payment', 'Booking dibuat', $userId);

            return $booking;
        });
    }

    /**
     * Confirm a booking (after payment verified)
     */
    public function confirmBooking(Booking $booking, ?int $changedBy = null): void
    {
        DB::transaction(function () use ($booking, $changedBy) {
            $oldStatus = $booking->status;

            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            // Update all related slots to booked
            $this->updateSlotStatus($booking, 'booked');

            // Log riwayat
            $this->logRiwayat($booking, $oldStatus, 'confirmed', 'Booking dikonfirmasi', $changedBy);

            // Notify member
            Notifikasi::kirim(
                $booking->user_id,
                'Booking Dikonfirmasi ✅',
                "Booking {$booking->kode_booking} untuk {$booking->lapangan->nama} pada {$booking->tanggal->format('d M Y')} telah dikonfirmasi.",
                'booking',
                ['booking_id' => $booking->id]
            );
        });
    }

    /**
     * Reject a booking
     */
    public function rejectBooking(Booking $booking, string $alasan, ?int $changedBy = null): void
    {
        DB::transaction(function () use ($booking, $alasan, $changedBy) {
            $oldStatus = $booking->status;

            $booking->update([
                'status' => 'rejected',
                'alasan_penolakan' => $alasan,
            ]);

            // Release slots
            $this->updateSlotStatus($booking, 'available');

            // Remove slot locks
            $this->slotLockService->releaseLocksForBooking($booking);

            $this->logRiwayat($booking, $oldStatus, 'rejected', "Ditolak: {$alasan}", $changedBy);

            Notifikasi::kirim(
                $booking->user_id,
                'Booking Ditolak ❌',
                "Booking {$booking->kode_booking} ditolak. Alasan: {$alasan}",
                'booking',
                ['booking_id' => $booking->id]
            );
        });
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Booking $booking, ?int $changedBy = null): void
    {
        DB::transaction(function () use ($booking, $changedBy) {
            $oldStatus = $booking->status;

            $refundAmount = 0;
            if ($oldStatus === 'confirmed') {
                $refundAmount = $booking->total_harga * 0.75;
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'refund_amount' => $refundAmount,
            ]);

            // Release slots
            $this->updateSlotStatus($booking, 'available');
            $this->slotLockService->releaseLocksForBooking($booking);

            $logMessage = 'Dibatalkan oleh user';
            if ($refundAmount > 0) {
                $logMessage .= ' (Refund 75%: Rp ' . number_format($refundAmount, 0, ',', '.') . ')';
            }

            $this->logRiwayat($booking, $oldStatus, 'cancelled', $logMessage, $changedBy);

            $notifBody = "Booking {$booking->kode_booking} telah dibatalkan.";
            if ($refundAmount > 0) {
                $notifBody .= " Pengembalian dana sebesar Rp " . number_format($refundAmount, 0, ',', '.') . " akan diproses oleh admin.";
            }

            Notifikasi::kirim(
                $booking->user_id,
                'Booking Dibatalkan',
                $notifBody,
                'booking',
                ['booking_id' => $booking->id]
            );
        });
    }

    /**
     * Complete a booking
     */
    public function completeBooking(Booking $booking, ?int $changedBy = null): void
    {
        $oldStatus = $booking->status;

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->logRiwayat($booking, $oldStatus, 'completed', 'Sesi selesai', $changedBy);

        Notifikasi::kirim(
            $booking->user_id,
            'Sesi Selesai! ⚽',
            "Sesi bermain Anda di {$booking->lapangan->nama} telah selesai. Jangan lupa kasih rating ya!",
            'booking',
            ['booking_id' => $booking->id]
        );
    }

    /**
     * Reschedule a booking
     */
    public function rescheduleBooking(Booking $booking, string $newTanggal, array $newSlotIds, ?int $changedBy = null): void
    {
        DB::transaction(function () use ($booking, $newTanggal, $newSlotIds, $changedBy) {
            if ($booking->status !== 'confirmed') {
                throw new \Exception('Hanya booking yang sudah dikonfirmasi yang dapat di-reschedule.');
            }

            // Lock and validate new slots
            $lapanganId = $booking->lapangan_id;
            $newSlots = SlotWaktu::whereIn('id', $newSlotIds)
                ->where('lapangan_id', $lapanganId)
                ->where('tanggal', $newTanggal)
                ->orderBy('jam_mulai')
                ->lockForUpdate()
                ->get();

            foreach ($newSlots as $slot) {
                if ($slot->status !== 'available') {
                    throw new \Exception("Slot {$slot->waktu} pada {$newTanggal} tidak tersedia.");
                }
            }

            $jamMulai = $newSlots->first()->jam_mulai;
            $jamSelesai = $newSlots->last()->jam_selesai;
            $durasiJam = $newSlots->count();

            // Calculate reschedule fee (5% of original total_harga)
            $rescheduleFee = $booking->total_harga * 0.05;

            // Release old slots BEFORE updating booking fields (because updateSlotStatus uses booking fields)
            $this->updateSlotStatus($booking, 'available');

            // Update booking
            $booking->update([
                'tanggal' => $newTanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'durasi_jam' => $durasiJam,
                'reschedule_fee' => $booking->reschedule_fee + $rescheduleFee,
            ]);

            // Reserve new slots
            $this->updateSlotStatus($booking, 'booked');

            $this->logRiwayat($booking, 'confirmed', 'confirmed', "Reschedule ke {$newTanggal} {$jamMulai}-{$jamSelesai}. Biaya admin 5%: Rp " . number_format($rescheduleFee, 0, ',', '.'), $changedBy);

            Notifikasi::kirim(
                $booking->user_id,
                'Booking Di-reschedule 📅',
                "Booking {$booking->kode_booking} berhasil di-reschedule ke {$newTanggal}. Harap bayar biaya admin 5% (Rp " . number_format($rescheduleFee, 0, ',', '.') . ") di kasir saat datang ke lapangan.",
                'booking',
                ['booking_id' => $booking->id]
            );
        });
    }

    /**
     * Update slot status for a booking
     */
    private function updateSlotStatus(Booking $booking, string $status): void
    {
        SlotWaktu::where('lapangan_id', $booking->lapangan_id)
            ->where('tanggal', $booking->tanggal)
            ->where('jam_mulai', '>=', $booking->jam_mulai)
            ->where('jam_selesai', '<=', $booking->jam_selesai)
            ->update(['status' => $status]);
    }

    /**
     * Log booking status change
     */
    private function logRiwayat(Booking $booking, ?string $oldStatus, string $newStatus, string $catatan, ?int $changedBy): void
    {
        RiwayatBooking::create([
            'booking_id' => $booking->id,
            'status_lama' => $oldStatus,
            'status_baru' => $newStatus,
            'catatan' => $catatan,
            'changed_by' => $changedBy,
        ]);
    }
}
