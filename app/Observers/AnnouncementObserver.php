<?php

namespace App\Observers;

use App\Models\Announcement;
use App\Models\User;
use Filament\Notifications\Notification;

class AnnouncementObserver
{
    public function created($announcement): void
    {
        // Kirim notifikasi saat announcement baru dibuat
        $users = User::where('role', 'member')->get();

        Notification::make()
            ->title('Pengumuman Baru')
            ->body($announcement->title)
            ->icon('heroicon-o-bell')
            ->iconColor('success')
            ->sendToDatabase($users);
    }

    public function updated($announcement): void
    {
        // Kirim notifikasi saat announcement diupdate
        if ($announcement->wasChanged('is_published') && $announcement->is_published) {
            $users = User::all();

            Notification::make()
                ->title('Pengumuman Diperbarui')
                ->body($announcement->title)
                ->icon('heroicon-o-arrow-path')
                ->iconColor('warning')
                ->sendToDatabase($users);
        }
    }
}
