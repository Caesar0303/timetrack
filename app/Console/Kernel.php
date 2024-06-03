<?php
namespace App\Console;

use App\Models\ShiftCard;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Задача для ежедневного сброса статуса карточек
        $schedule->call(function () {
            ShiftCard::where('status', '!=', 0)->update(['status' => 0]);
            Log::info('Card statuses have been reset to 0.');
        })->everySecond();
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
