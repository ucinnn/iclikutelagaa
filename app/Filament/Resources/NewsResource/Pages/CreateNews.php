<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (! isset($data['updated_by']) || blank($data['updated_by'])) {
            $data['updated_by'] = Filament::auth()->user()?->name ?? 'System';
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = implode(',', $data['tags']);
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = parent::handleRecordCreation($data);

        if ($record->status === 'published') {
            $this->notifyPublished($record);
        }

        return $record;
    }

    /**
     * Kirim notifikasi ketika berita diterbitkan.
     */
    protected function notifyPublished($record): void
    {
        Notification::make()
            ->title('Berita berhasil diterbitkan')
            ->body("Judul: {$record->title}")
            ->success()
            ->send();
    }
}
