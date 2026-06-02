<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImportResource\Pages;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class ImportResource extends Resource
{
    protected static ?string $model = Import::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?int $navigationSort = 99;

    public static function getNavigationLabel(): string
    {
        return __('import.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('import.navigation_group');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('file_name')
                    ->label(__('import.form.file_name'))
                    ->disabled(),

                Forms\Components\TextInput::make('importer')
                    ->label(__('import.form.importer'))
                    ->disabled(),

                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\TextInput::make('total_rows')
                            ->label(__('import.form.total_rows'))
                            ->disabled()
                            ->numeric(),

                        Forms\Components\TextInput::make('processed_rows')
                            ->label(__('import.form.processed'))
                            ->disabled()
                            ->numeric(),

                        Forms\Components\TextInput::make('successful_rows')
                            ->label(__('import.form.success'))
                            ->disabled()
                            ->numeric(),

                        Forms\Components\TextInput::make('failed_rows')
                            ->label(__('import.form.failed'))
                            ->disabled()
                            ->numeric(),
                    ]),

                Forms\Components\Textarea::make('error_message')
                    ->label(__('import.form.error_message'))
                    ->disabled()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('import.table.imported_by'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('file_name')
                    ->label(__('import.table.file_name'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 30) {
                            return $state;
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('importer')
                    ->label(__('import.table.type'))
                    ->formatStateUsing(
                        fn(string $state): string =>
                        str_replace('App\\Filament\\Imports\\', '', $state)
                    )
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('total_rows')
                    ->label(__('import.table.total'))
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('processed_rows')
                    ->label(__('import.table.processed'))
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('successful_rows')
                    ->label(__('import.table.success'))
                    ->numeric()
                    ->alignCenter()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('failed_rows')
                    ->label(__('import.table.failed'))
                    ->numeric()
                    ->alignCenter()
                    ->color(fn($state) => $state > 0 ? 'danger' : 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('import.table.status'))
                    ->badge()
                    ->formatStateUsing(function ($record) {
                        if ($record->completed_at) {
                            return __('import.status.completed');
                        }
                        if ($record->processed_rows > 0) {
                            return __('import.status.processing');
                        }
                        return __('import.status.pending');
                    })
                    ->color(fn($record) => match (true) {
                        $record->completed_at && $record->failed_rows === 0 => 'success',
                        $record->completed_at && $record->failed_rows > 0 => 'warning',
                        $record->processed_rows > 0 => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('import.table.import_date'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('importer')
                    ->label(__('import.filter.import_type_label'))
                    ->options([
                        // Pastikan key di sini sesuai dengan nilai di database
                        'App\\Filament\\Imports\\UserImporter' => __('import.filter.importer_options.user_import'),
                        // Tambahkan importer lain di sini
                    ]),

                Tables\Filters\Filter::make('status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => __('import.status.pending'),
                                'processing' => __('import.status.processing'),
                                'completed' => __('import.status.completed'),
                                'failed' => __('import.status.failed'),
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['status'] === 'pending',
                                fn(Builder $query) => $query->whereNull('completed_at')->where('processed_rows', 0)
                            )
                            ->when(
                                $data['status'] === 'processing',
                                fn(Builder $query) => $query->whereNull('completed_at')->where('processed_rows', '>', 0)
                            )
                            ->when(
                                $data['status'] === 'completed',
                                fn(Builder $query) => $query->whereNotNull('completed_at')->where('failed_rows', 0)
                            )
                            ->when(
                                $data['status'] === 'failed',
                                fn(Builder $query) => $query->whereNotNull('completed_at')->where('failed_rows', '>', 0)
                            );
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label(__('import.filter.from_date')),
                        Forms\Components\DatePicker::make('until')
                            ->label(__('import.filter.until_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('download')
                    ->label(__('import.action.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(
                        fn(Import $record): string =>
                        route('filament.admin.resources.imports.download', $record)
                    )
                    ->openUrlInNewTab()
                    ->visible(
                        fn(Import $record): bool =>
                        file_exists(storage_path('app/' . $record->file_path))
                    ),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('10s'); // Auto refresh setiap 10 detik
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImports::route('/'),
            'view' => Pages\ViewImport::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Tampilkan jumlah import yang sedang processing
        return static::getModel()::whereNull('completed_at')
            ->where('processed_rows', '>', 0)
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
