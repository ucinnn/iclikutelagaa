<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAnnouncement extends EditRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        // Kirim notifikasi jika baru dipublikasikan
        if ($record->wasChanged('status') && $record->status === 'published') {
            $query = User::receivingNotifications();

            if (!empty($record->target_roles)) {
                $query->withAnyRole($record->target_roles);
            }

            $users = $query->get();

            if ($users->isNotEmpty()) {
                Notification::make()
                    ->title($record->title)
                    ->body($record->body)
                    ->icon($record->icon ?? 'heroicon-o-bell')
                    ->iconColor($record->getTypeColor())
                    ->sendToDatabase($users);

                Notification::make()
                    ->title('Berhasil')
                    ->body('Pengumuman dipublikasikan dan terkirim ke ' . $users->count() . ' users')
                    ->success()
                    ->send();
            }
        }
    }
}