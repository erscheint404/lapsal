<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\SlotLock;
use App\Models\SlotWaktu;
use Illuminate\Support\Facades\DB;

class SlotLockService
{
    /**
     * Lock a slot for a user (10 minutes by default)
     */
    public function lockSlot(int $slotWaktuId, int $userId, int $durasiMenit = 10): SlotLock
    {
        return DB::transaction(function () use ($slotWaktuId, $userId, $durasiMenit) {
            $slot = SlotWaktu::lockForUpdate()->findOrFail($slotWaktuId);

            // Check if slot is available
            if ($slot->status !== 'available') {
                // Check if there's an existing lock by this user
                $existingLock = SlotLock::where('slot_waktu_id', $slotWaktuId)
                    ->where('user_id', $userId)
                    ->active()
                    ->first();

                if ($existingLock) {
                    return $existingLock;
                }

                throw new \Exception('Slot sedang dipesan orang lain. Silakan pilih slot lain atau tunggu beberapa menit.');
            }

            // Remove any expired locks
            SlotLock::where('slot_waktu_id', $slotWaktuId)->expired()->delete();

            // Create lock
            $lock = SlotLock::create([
                'slot_waktu_id' => $slotWaktuId,
                'user_id' => $userId,
                'locked_at' => now(),
                'expired_at' => now()->addMinutes($durasiMenit),
            ]);

            // Update slot status
            $slot->update(['status' => 'reserved']);

            return $lock;
        });
    }

    /**
     * Lock multiple slots for a user
     */
    public function lockMultipleSlots(array $slotWaktuIds, int $userId, int $durasiMenit = 10): array
    {
        $locks = [];
        foreach ($slotWaktuIds as $slotId) {
            $locks[] = $this->lockSlot($slotId, $userId, $durasiMenit);
        }
        return $locks;
    }

    /**
     * Release a specific lock
     */
    public function releaseLock(SlotLock $lock): void
    {
        DB::transaction(function () use ($lock) {
            $slot = $lock->slotWaktu;

            // Only release if not already booked
            if ($slot->status === 'reserved') {
                $slot->update(['status' => 'available']);
            }

            $lock->delete();
        });
    }

    /**
     * Release all expired locks system-wide
     */
    public function releaseExpiredLocks(): int
    {
        $expiredLocks = SlotLock::expired()->with('slotWaktu')->get();
        $count = 0;

        foreach ($expiredLocks as $lock) {
            DB::transaction(function () use ($lock) {
                $slot = $lock->slotWaktu;

                if ($slot && $slot->status === 'reserved') {
                    $slot->update(['status' => 'available']);
                }

                $lock->delete();
            });
            $count++;
        }

        // Also expire pending_payment bookings that have been pending too long (over 15 minutes)
        $expiredBookings = Booking::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subMinutes(15))
            ->get();

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'expired']);

                // Release related slots
                SlotWaktu::where('lapangan_id', $booking->lapangan_id)
                    ->where('tanggal', $booking->tanggal)
                    ->where('jam_mulai', '>=', $booking->jam_mulai)
                    ->where('jam_selesai', '<=', $booking->jam_selesai)
                    ->where('status', 'reserved')
                    ->update(['status' => 'available']);
            });
        }

        return $count;
    }

    /**
     * Release locks for a specific booking
     */
    public function releaseLocksForBooking(Booking $booking): void
    {
        $slotIds = SlotWaktu::where('lapangan_id', $booking->lapangan_id)
            ->where('tanggal', $booking->tanggal)
            ->where('jam_mulai', '>=', $booking->jam_mulai)
            ->where('jam_selesai', '<=', $booking->jam_selesai)
            ->pluck('id');

        $locks = SlotLock::whereIn('slot_waktu_id', $slotIds)
            ->where('user_id', $booking->user_id)
            ->get();

        foreach ($locks as $lock) {
            $lock->delete();
        }
    }

    /**
     * Get remaining seconds for a user's active locks
     */
    public function getRemainingSeconds(int $userId, array $slotIds): int
    {
        $lock = SlotLock::whereIn('slot_waktu_id', $slotIds)
            ->where('user_id', $userId)
            ->active()
            ->orderBy('expired_at', 'asc')
            ->first();

        return $lock ? $lock->remaining_seconds : 0;
    }
}
