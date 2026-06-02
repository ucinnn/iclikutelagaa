<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\jobs\SendDailyNotifications;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            \App\Models\News::where('status', 'schedule')
                ->where('published_at', '<=', now())
                ->update(['status' => 'published']);
        })->everyMinute(); // jalankan tiap menit
        $schedule->command('news:publish-scheduled')->everyMinute();
        // $schedule->job(new SendDailyNotification())->dailyAt('09:00');
    }
}
