<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getNavigationGroup(): ?string
    {
        return __('news.title_group');
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Buat array yang berisi peran (role) yang diizinkan
        $allowedRoles = ['author', 'admin', 'superadmin'];

        // Periksa apakah peran pengguna saat ini ada di dalam array tersebut
        return in_array(Filament::auth()->user()?->role, $allowedRoles);
    }

    public static function getNavigationLabel(): string
    {
        return __('category.navigation_label');
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function canDelete(Model $record): bool
    {
        $user = Filament::auth()->user();
        return in_array($user->role, ['admin', 'superadmin']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label(__('category.fields.name'))
                ->required()
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->label(__('category.fields.slug'))
                ->disabled()
                ->dehydrated(),

            Hidden::make('created_by')
                ->dehydrated()
                ->default(fn() => Filament::auth()->user()?->name ?? 'System'),

            Hidden::make('updated_by')
                ->dehydrated()
                ->default(fn() => Filament::auth()->user()?->name . ' (' . Filament::auth()->user()?->NIK . ')' ?? 'System'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label(__('category.table.no'))
                    ->rowIndex()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label(__('category.table.name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_by')
                    ->label(__('category.table.created_by'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('category.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_by')
                    ->label(__('category.table.updated_by'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label(__('category.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('keterangan')
                    ->label(__('category.table.information'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_by')
                    ->label(__('category.filters.created_by'))
                    ->options(
                        User::query()
                            ->select('created_by')
                            ->distinct()
                            ->pluck('created_by', 'created_by')
                            ->filter()
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => in_array(Filament::auth()->user()->role, ['admin', 'superadmin'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => in_array(Filament::auth()->user()->role, ['admin', 'superadmin'])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['updated_by']) || blank($data['updated_by'])) {
            $data['updated_by'] = Filament::auth()->user()?->name ?? 'System';
        }
        return $data;
    }
}
