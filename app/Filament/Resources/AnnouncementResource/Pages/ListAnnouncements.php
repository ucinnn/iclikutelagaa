<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sendAnnouncement')
                ->label('Kirim Pengumuman Cepat')
                ->icon('heroicon-o-megaphone')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->label('Judul')
                        ->placeholder('Masukkan judul pengumuman'),

                    \Filament\Forms\Components\Textarea::make('body')
                        ->required()
                        ->label('Pesan')
                        ->rows(4)
                        ->placeholder('Masukkan isi pesan'),

                    \Filament\Forms\Components\Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'info' => 'Info',
                            'success' => 'Success',
                            'warning' => 'Warning',
                            'danger' => 'Danger',
                        ])
                        ->default('info')
                        ->required(),

                    \Filament\Forms\Components\CheckboxList::make('target_roles')
                        ->label('Target Role (Opsional)')
                        ->options([
                            'user' => 'User',
                            'admin' => 'Admin',
                            'author' => 'Author',
                            'superadmin' => 'Superadmin',
                        ])
                        ->helperText('Kosongkan untuk kirim ke semua user')
                        ->columns(3),
                ])
                ->action(function (array $data) {
                    // Simpan ke database
                    $announcement = Announcement::create([
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'type' => $data['type'],
                        'status' => 'published',
                        'published_at' => now(),
                        'target_roles' => $data['target_roles'] ?? null,
                    ]);

                    // Query users berdasarkan role
                    $users = User::query()
                        ->where('receive_notifications', 1)
                        ->when(!empty($data['target_roles']), function ($query) use ($data) {
                            $query->whereIn('role', $data['target_roles']);
                        })
                        ->get();

                    // Kirim notifikasi
                    $iconColor = match ($data['type']) {
                        'success' => 'success',
                        'warning' => 'warning',
                        'danger' => 'danger',
                        default => 'info',
                    };

                    Notification::make()
                        ->title($data['title'])
                        ->body($data['body'])
                        ->icon('heroicon-o-bell')
                        ->iconColor($iconColor)
                        ->sendToDatabase($users);

                    // Feedback sukses
                    Notification::make()
                        ->title('Berhasil')
                        ->body('Pengumuman terkirim ke ' . $users->count() . ' pengguna.')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()
                ->label('Buat Pengumuman')
                ->icon('heroicon-o-plus'),
        ];
    }
}
