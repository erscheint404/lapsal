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
