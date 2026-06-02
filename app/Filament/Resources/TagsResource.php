<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagsResource\Pages;
use App\Filament\Resources\TagsResource\RelationManagers;
use App\Models\Tags;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class TagsResource extends Resource
{
    protected static ?string $model = Tags::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getNavigationGroup(): ?string
    {
        return __('news.title_group');
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Buat array yang berisi peran (role) yang diizinkan
        $allowedRoles = ['author', 'admin', 'superadmin'];

        // Periksa apakah peran pengguna saat ini ada di dalam array tersebut
        return in_array(Filament::auth()->user()?->role, $allowedRoles);
    }
    public static function canDelete(Model $record): bool
    {
        $user = Filament::auth()->user();

        return in_array($user->role, ['admin', 'super_admin']);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                        fn($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),
                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(),
                Hidden::make('created_by')
                    ->dehydrated() // pastikan field ini selalu ikut disimpan
                    ->default(fn() => Filament::auth()->user()?->name ?? 'System'),

                Hidden::make('updated_by')
                    ->dehydrated() // pastikan field ini selalu ikut disimpan
                    ->default(fn() => Filament::auth()->user()?->name . ' (' . Filament::auth()->user()?->NIK . ')' ?? 'System'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('created_by')
                    ->label('Created By')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_by')
                    ->label('Updated By')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('keterangan')
                    ->label('Information')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_by')
                    ->label('Created By')
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTags::route('/create'),
            'edit' => Pages\EditTags::route('/{record}/edit'),
        ];
    }
}
