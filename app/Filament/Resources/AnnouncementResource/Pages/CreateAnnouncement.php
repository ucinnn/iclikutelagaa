<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Method ini benar karena di Page level $this->record tersedia
    protected function afterCreate(): void
    {
        $record = $this->record;

        // Hanya kirim jika status published
        if ($record->status !== 'published') {
            return;
        }

        // Ambil semua user yang berhak
        $users = User::query()
            ->when(
                !empty($record->target_roles),
                fn($q) => $q->whereHas('roles', fn($r) => $r->whereIn('name', $record->target_roles))
            )
            ->get();

        foreach ($users as $user) {
            Notification::make()
                ->title($record->title)
                ->body($record->body)
                ->icon($record->icon ?? 'heroicon-o-bell')
                ->iconColor($record->getTypeColor())
                ->sendToDatabase($user); // ✅ HARUS per-user
        }

        // Notifikasi feedback admin
        Notification::make()
            ->title('Berhasil')
            ->body("Pengumuman dipublikasikan dan terkirim ke {$users->count()} user")
            ->success()
            ->send();
    }
}
