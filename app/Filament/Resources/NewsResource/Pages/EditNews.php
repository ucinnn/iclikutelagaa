<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\EditRecord;
use App\Notifications\NewsPublishedNotification;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    // 🟢 Aktifkan auto refresh setiap 5 detik
    protected static bool $polling = true;
    protected static ?string $pollingInterval = '5s';

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $originalStatus = $record->status;

        $record->update($data);

        if ($originalStatus === 'scheduled' && $record->status === 'published') {
            $recipients = User::whereIn('role', ['superadmin', 'admin', 'author'])->get();

            foreach ($recipients as $user) {
                $user->notify(new NewsPublishedNotification($record));

                FilamentNotification::make()
                    ->title('Berita Telah Dipublikasikan')
                    ->body("Berita **{$record->title}** telah diterbitkan.")
                    ->success()
                    ->sendToDatabase($user);
            }
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return NewsResource::getUrl();
    }

    // 🟢 Fungsi untuk memeriksa status terkini dan refresh halaman jika berubah
    protected function getListeners(): array
    {
        return [
            'refresh' => '$refresh',
        ];
    }
}