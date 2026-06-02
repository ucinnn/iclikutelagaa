<?php

namespace App\Notifications\Channels;

use Filament\Notifications\Notification as FilamentNotification;

class FilamentChannel
{
    public function send($notifiable, $notification)
    {
        if (method_exists($notification, 'toFilament')) {
            $filamentNotification = $notification->toFilament($notifiable);

            if ($filamentNotification instanceof FilamentNotification) {
                $filamentNotification->sendToDatabase($notifiable);
            }
        }
    }
    public function via($notifiable)
    {
        return ['database', \App\Notifications\Channels\FilamentChannel::class];
    }
}
