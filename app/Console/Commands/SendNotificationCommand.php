<?php

namespace App\Console\Commands;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class SendNotificationCommand extends Command
{
    protected $signature = 'notify:send {--title=} {--body=}';
    protected $description = 'Send notification to all users';

    public function handle(): int
    {
        $title = $this->option('title') ?? 'Notifikasi';
        $body = $this->option('body') ?? 'Anda memiliki pesan baru';

        $users = User::all();
        
        Notification::make()
            ->title($title)
            ->body($body)
            ->icon('heroicon-o-bell')
            ->iconColor('success')
            ->sendToDatabase($users);

        $this->info("Notifikasi terkirim ke {$users->count()} users!");

        return self::SUCCESS;
    }
}