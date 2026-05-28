<?php

namespace App\Console\Commands;

use App\Services\SlotLockService;
use Illuminate\Console\Command;

class ReleaseExpiredSlots extends Command
{
    protected $signature = 'slots:release-expired';
    protected $description = 'Release expired slot locks and expire stale pending bookings';

    public function handle(SlotLockService $slotLockService): int
    {
        $count = $slotLockService->releaseExpiredLocks();

        if ($count > 0) {
            $this->info("Released {$count} expired slot lock(s).");
        }

        return self::SUCCESS;
    }
}
