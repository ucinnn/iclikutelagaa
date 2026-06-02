<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengumuman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('body')
                            ->label('Pesan')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'info' => 'Info',
                                'success' => 'Success',
                                'warning' => 'Warning',
                                'danger' => 'Danger',
                            ])
                            ->default('info')
                            ->required(),

                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (Heroicon)')
                            ->placeholder('heroicon-o-bell')
                            ->helperText('Opsional. Contoh: heroicon-o-bell, heroicon-o-megaphone'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Publikasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->default('draft')
                            ->required()
                            ->live(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->visible(fn(Forms\Get $get) => $get('status') === 'published')
                            ->helperText('Kosongkan untuk publish sekarang'),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Tanggal Kadaluarsa')
                            ->helperText('Pengumuman akan hilang setelah tanggal ini'),

                        Forms\Components\Toggle::make('is_pinned')
                            ->label('Pin Pengumuman')
                            ->helperText('Tampilkan di atas')
                            ->default(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Target Audience')
                    ->schema([
                        Forms\Components\CheckboxList::make('target_roles')
                            ->label('Target Role')
                            ->options([
                                'admin' => 'Admin',
                                'editor' => 'Editor',
                                'author' => 'Author',
                                'subscriber' => 'Subscriber',
                                'member' => 'Member',
                                'user' => 'User',
                            ])
                            ->helperText('Kosongkan untuk kirim ke semua role')
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_pinned')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('body')
                    ->label('Pesan')
                    ->limit(60)
                    ->searchable(),

                // Ganti BadgeColumn dengan TextColumn + badge()
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'info' => 'info',
                        'success' => 'success',
                        'warning' => 'warning',
                        'danger' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('readers_count')
                    ->label('Dibaca')
                    ->counts('readers')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'info' => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'danger' => 'Danger',
                    ]),

                Tables\Filters\TernaryFilter::make('is_pinned')
                    ->label('Pinned')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Announcement $record) => $record->status === 'draft')
                    ->action(function (Announcement $record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);

                        self::sendNotificationToUsers($record);

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Pengumuman dipublikasikan dan notifikasi terkirim.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }

    // Method helper untuk kirim notifikasi (static method)
    public static function sendNotificationToUsers(Announcement $announcement): void
    {
        $query = User::receivingNotifications();

        // Filter berdasarkan target roles
        if (!empty($announcement->target_roles)) {
            $query->withAnyRole($announcement->target_roles);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            return;
        }

        // Kirim notifikasi
        Notification::make()
            ->title($announcement->title)
            ->body($announcement->body)
            ->icon($announcement->icon ?? 'heroicon-o-bell')
            ->iconColor($announcement->getTypeColor())
            ->sendToDatabase($users);
    }

    // HAPUS method afterCreate() dari sini - pindah ke CreateAnnouncement page
}
