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
        // Example scheduled tasks for Bagisto
        
        // Clean up expired sessions daily at 2 AM
        $schedule->command('session:gc')->daily()->at('02:00');
        
        // Send abandoned cart emails every hour
        $schedule->command('bagisto:send-abandoned-cart-emails')
                 ->hourly()
                 ->withoutOverlapping();
        
        // Update currency rates daily at 6 AM
        $schedule->command('bagisto:update-currency-rates')
                 ->daily()
                 ->at('06:00');
        
        // Clean up old logs weekly
        $schedule->command('log:clear')
                 ->weekly()
                 ->sundays()
                 ->at('03:00');
        
        // Generate sitemap daily at 4 AM
        $schedule->command('bagisto:generate-sitemap')
                 ->daily()
                 ->at('04:00');
        
        // Send newsletter emails every Monday at 9 AM
        $schedule->command('bagisto:send-newsletter')
                 ->weekly()
                 ->mondays()
                 ->at('09:00');
        
        // Backup database daily at 1 AM (if backup command exists)
        $schedule->command('backup:run')
                 ->daily()
                 ->at('01:00')
                 ->onFailure(function () {
                     \Log::error('Database backup failed');
                 });
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
