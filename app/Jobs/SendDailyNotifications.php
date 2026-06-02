<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDailyNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $users = User::where('is_active', true)->get();
        
        Notification::make()
            ->title('Reminder Harian')
            ->body('Jangan lupa cek update terbaru hari ini!')
            ->icon('heroicon-o-clock')
            ->iconColor('info')
            ->sendToDatabase($users);
    }
}