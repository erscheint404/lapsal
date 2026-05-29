<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Release expired slot locks every minute
        $schedule->command('slots:release-expired')->everyMinute();

        // Auto-complete bookings whose session time has passed
        $schedule->call(function () {
            $bookings = \App\Models\Booking::where('status', 'confirmed')
                ->where('tanggal', '<', today())
                ->orWhere(function ($q) {
                    $q->where('tanggal', today())
                      ->where('jam_selesai', '<', now()->format('H:i:s'));
                })
                ->where('status', 'confirmed')
                ->get();

            $bookingService = app(\App\Services\BookingService::class);
            foreach ($bookings as $booking) {
                $bookingService->completeBooking($booking);
            }
        })->everyFiveMinutes();

        // Auto-generate slots for 14 days ahead every day at midnight (rolling 14-day window)
        $schedule->call(function () {
            $activeFields = \App\Models\Lapangan::aktif()->get();
            $targetDate = today()->addDays(14)->format('Y-m-d');
            
            // Get operational hours from settings
            $jamBuka = (int)\App\Models\Pengaturan::getValue('jam_buka', '8');
            $jamTutup = (int)\App\Models\Pengaturan::getValue('jam_tutup', '23');

            foreach ($activeFields as $lapangan) {
                for ($jam = $jamBuka; $jam < $jamTutup; $jam++) {
                    \App\Models\SlotWaktu::firstOrCreate(
                        [
                            'lapangan_id' => $lapangan->id,
                            'tanggal' => $targetDate,
                            'jam_mulai' => sprintf('%02d:00:00', $jam),
                        ],
                        [
                            'jam_selesai' => sprintf('%02d:00:00', $jam + 1),
                            'status' => 'available',
                        ]
                    );
                }
            }
        })->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
