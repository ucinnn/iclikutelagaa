<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WistleBlowingResource\Pages;
use App\Models\WistleBlowing;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class WistleBlowingResource extends Resource
{
    protected static ?string $model = WistleBlowing::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Whistle Blowing';

    protected static ?string $modelLabel = 'Laporan';

    protected static ?string $pluralModelLabel = 'Laporan Whistle Blowing';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Laporan')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Nama Pelaku')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(2),

                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'Korupsi'         => 'Korupsi',
                                'Gratifikasi'     => 'Gratifikasi',
                                'Kecurangan'      => 'Kecurangan',
                                'Pelecehan'       => 'Pelecehan',
                                'Pelanggaran SOP' => 'Pelanggaran SOP',
                                'Lainnya'         => 'Lainnya',
                            ])
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('division')
                            ->label('Divisi Terkait')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('description')
                            ->label('Kronologi Kejadian')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(6)
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status Laporan')
                            ->options([
                                'pending'  => 'Pending',
                                'process'  => 'Diproses',
                                'resolved' => 'Selesai',
                                'rejected' => 'Ditolak',
                            ])
                            ->required()
                            ->native(false)
                            ->dehydrated(true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Pelapor')
                    ->schema([
                        Placeholder::make('user_name')
                            ->label('Nama')
                            ->content(fn(WistleBlowing $record): string => $record->user?->name ?? '-'),

                        Placeholder::make('user_email')
                            ->label('Email')
                            ->content(fn(WistleBlowing $record): string => $record->user?->email ?? '-'),
                    ])
                    ->columns(2),

                Section::make('Bukti Pendukung')
                    ->schema([
                        Placeholder::make('proof_files')
                            ->label('File Bukti')
                            ->content(function (WistleBlowing $record): HtmlString {
                                $files = $record->proof;
                                if (empty($files)) {
                                    return new HtmlString('<span class="text-gray-400 text-sm">Tidak ada file</span>');
                                }

                                $html = '<div class="flex flex-wrap gap-2 items-start">';

                                foreach ($files as $file) {
                                    $filePath = is_array($file) ? ($file['path'] ?? '') : $file;
                                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($file);
                                    $cleanFile = preg_replace('#^storage/#', '', $filePath);
                                    $url       = asset('storage/' . $cleanFile);
                                    $ext       = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                                    $iconColor = match($ext) {
                                        'pdf'                       => 'text-red-500',
                                        'doc', 'docx'               => 'text-blue-500',
                                        'xls', 'xlsx'               => 'text-green-600',
                                        'jpg', 'jpeg', 'png', 'gif' => 'text-purple-500',
                                        'mp4', 'avi', 'mov'         => 'text-pink-500',
                                        'zip', 'rar'                => 'text-yellow-600',
                                        default                     => 'text-gray-400',
                                    };

                                    $html .= '
                                    <a href="' . e($url) . '" target="_blank" download="' . e($fileName) . '"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-600 rounded-lg text-xs border border-gray-200 hover:border-blue-300 transition">
                                        <svg class="w-4 h-4 flex-shrink-0 ' . $iconColor . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="text-left">
                                            <p class="font-medium truncate max-w-[160px]">' . e($fileName) . '</p>
                                            <p class="text-gray-400 uppercase text-[10px]">' . $ext . '</p>
                                        </div>
                                        <svg class="w-3 h-3 ml-1 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>';
                                }

                                $zipUrl = route('wistle-blowing.download-zip', $record->id);
                                $html .= '
                                <a href="' . $zipUrl . '"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold border border-indigo-700 transition shadow-sm">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download Semua (ZIP)
                                </a>';

                                $html .= '</div>';
                                return new HtmlString($html);
                            })
                            ->columnSpanFull(),

                        Placeholder::make('proof_links')
                            ->label('Link Bukti')
                            ->content(function (WistleBlowing $record): HtmlString {
                                $links = $record->links;
                                if (empty($links)) {
                                    return new HtmlString('<span class="text-gray-400 text-sm">Tidak ada link</span>');
                                }

                                $html = '<div class="flex flex-col gap-2">';
                                foreach ($links as $link) {
                                    $html .= '
                                    <a href="' . e($link) . '" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs border border-blue-100 hover:border-blue-300 transition">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span class="truncate max-w-md">' . e($link) . '</span>
                                    </a>';
                                }
                                $html .= '</div>';
                                return new HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label(__('No'))
                    ->rowIndex()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Pelapor')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => Auth::user()?->role === 'superadmin'),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'Korupsi'         => 'danger',
                        'Gratifikasi'     => 'warning',
                        'Kecurangan'      => 'danger',
                        'Pelecehan'       => 'warning',
                        'Pelanggaran SOP' => 'info',
                        default           => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Nama Pelaku')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(30)
                    ->wrap(),

                TextColumn::make('division')
                    ->label('Divisi')
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Kronologi')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn(WistleBlowing $record): string => $record->description ?? ''),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'resolved' => 'success',
                        'process'  => 'warning',
                        'rejected' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match($state) {
                        'pending'  => 'Pending',
                        'process'  => 'Diproses',
                        'resolved' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default    => ucfirst($state),
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'process'  => 'Diproses',
                        'resolved' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ]),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Korupsi'         => 'Korupsi',
                        'Gratifikasi'     => 'Gratifikasi',
                        'Kecurangan'      => 'Kecurangan',
                        'Pelecehan'       => 'Pelecehan',
                        'Pelanggaran SOP' => 'Pelanggaran SOP',
                        'Lainnya'         => 'Lainnya',
                    ]),

                // ── Filter rentang waktu ──────────────────────────────────────
                Filter::make('created_at')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $q) => $q->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $q) => $q->whereDate('created_at', '<=', $data['until'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['from'])->format('d/m/Y');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['until'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
            ])
            ->filtersFormColumns(2) // filter tampil 2 kolom agar lebih rapi
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),

                Action::make('set_process')
                    ->label('Proses')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn(WistleBlowing $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Ubah Status ke Diproses?')
                    ->modalDescription('Laporan akan ditandai sedang diproses.')
                    ->action(function (WistleBlowing $record): void {
                        $record->status = 'process';
                        $record->save();
                        Notification::make()
                            ->title('Status diperbarui ke Diproses')
                            ->success()
                            ->send();
                    }),

                Action::make('set_resolved')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(WistleBlowing $record): bool => $record->status === 'process')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Laporan Selesai?')
                    ->modalDescription('Laporan ini akan ditandai telah selesai ditangani.')
                    ->action(function (WistleBlowing $record): void {
                        $record->status = 'resolved';
                        $record->save();
                        Notification::make()
                            ->title('Laporan ditandai Selesai')
                            ->success()
                            ->send();
                    }),

                Action::make('set_rejected')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(WistleBlowing $record): bool => in_array($record->status, ['pending', 'process']))
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Laporan Ini?')
                    ->modalDescription('Laporan akan ditolak dan tidak diproses lebih lanjut.')
                    ->action(function (WistleBlowing $record): void {
                        $record->status = 'rejected';
                        $record->save();
                        Notification::make()
                            ->title('Laporan ditolak')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWistleBlowings::route('/'),
            'view'  => Pages\ViewWistleBlowing::route('/{record}'),
            'edit'  => Pages\EditWistleBlowing::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}