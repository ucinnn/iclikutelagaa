<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Hidden;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\SelectFilter;
use Filament\Tables\Actions\EditAction;
use App\Mail\UserCreatedNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use App\Filament\Imports\UserImporter;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('NIK')
                ->label('NIK')
                ->nullable()
                ->maxLength(25)
                ->disabled(
                    fn(?User $record) =>
                    // Hanya superadmin bisa update (saat edit)
                    $record !== null && Filament::auth()->user()->role !== 'superadmin'
                )
                // ->disabled(fn() => !in_array(Filament::auth()->user()->role, ['admin', 'superadmin']))
                ->validationMessages(['unique' => 'NIK already registered.'])
                ->unique(
                    User::class,
                    'NIK',
                    ignoreRecord: true
                ),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('role')
                ->options(function () {
                    $userRole = Filament::auth()->user()->role;

                    if ($userRole === 'superadmin') {
                        return [
                            'admin' => 'Admin',
                            'author' => 'Author',
                            'user' => 'User',
                        ];
                    }

                    if ($userRole === 'admin') {
                        return [
                            'author' => 'Author',
                            'user' => 'User',
                        ];
                    }

                    return [];
                })
                ->required(
                    fn(?User $record) =>
                    // Wajib saat create
                    ($record === null) ||
                        // Atau saat edit, jika superadmin sedang mengedit user lain (bukan dirinya sendiri)
                        ($record !== null &&
                            Filament::auth()->user()?->role === 'superadmin' &&
                            Filament::auth()->user()?->id !== $record->id) || ($record !== null &&
                            Filament::auth()->user()?->role === 'admin' &&
                            Filament::auth()->user()?->id !== $record->id)
                )
                ->dehydrated(fn($state) => !is_null($state)) // Kirim nilai jika tidak null
                ->validationMessages(['required' => 'Field equired.'])
                ->disabled(function (?User $record) {
                    $user = Filament::auth()->user();

                    // Superadmin tidak bisa edit dirinya sendiri
                    if ($user->role === 'superadmin') {
                        return $record && $user->id === $record->id;
                    }

                    // Admin tidak bisa edit superadmin/admin
                    if ($user->role === 'admin') {
                        // Saat create, admin bisa input (tidak disable)
                        if ($record === null) {
                            return false;
                        }

                        // Saat edit, cek role target
                        if (in_array($record->role, ['admin', 'superadmin'])) {
                            return true; // disable
                        }

                        return false; // bisa edit user biasa/author
                    }

                    // Role lain (author/user) tidak bisa akses
                    return true;
                }),


            Forms\Components\TextInput::make('email')
                ->email()
                ->required(
                    //Wajib saat create
                    fn(?User $record) => ($record === null) ||
                        // Wajib saat edit, jika superadmin sedang mengedit user lain dan dirinya sendiri
                        (($record !== null &&
                            Filament::auth()->user()?->role === 'superadmin' &&
                            Filament::auth()->user()?->id !== $record->id) || Filament::auth()->user()?->role === 'superadmin')
                )
                ->disabled(
                    fn(?User $record) =>
                    // Hanya superadmin bisa update (saat edit)
                    $record !== null && Filament::auth()->user()->role !== 'superadmin'
                )
                ->validationMessages(['unique' => 'Email already registered.'])
                ->unique(User::class, 'email', ignoreRecord: true),


            Forms\Components\TextInput::make('password')
                ->password()
                ->maxLength(255)
                ->minLength(8) // Password minimal 8 karakter
                ->required(fn(?User $record) => $record === null) // hanya wajib saat create
                ->revealable(fn(?User $record) => Filament::auth()->user()?->id === $record?->id || ($record === null))
                ->dehydrateStateUsing(function ($state, Forms\Components\TextInput $component) {
                    if (filled($state)) {
                        // Hanya simpan password_plain saat edit
                        if ($component->getLivewire()->record?->exists) {
                            $component->getLivewire()->record->password_plain = $state;
                        }
                        return Hash::make($state);
                    }
                    return null;
                })
                ->dehydrated(fn($state) => filled($state)) // hanya kirim ke DB jika diisi
                ->afterStateHydrated(fn($component) => $component->state('')), // kosongkan saat edit

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

                TextColumn::make('NIK')
                    ->label('NIK')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false), // selalu tampil awal

                TextColumn::make('role')
                    ->label('Role')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

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
                    ->toggleable(isToggledHiddenByDefault: true), // disembunyikan default

                TextColumn::make('updated_by')
                    ->label('Updated By')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // disembunyikan default

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // disembunyikan default

                TextColumn::make('keterangan')
                    ->label('Information')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->limit(255)
                    ->toggleable(isToggledHiddenByDefault: true), // disembunyikan default
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'superadmin' => 'Superadmin',
                        'admin' => 'Admin',
                        'author' => 'Author',
                        'user' => 'User',
                    ]),
                Tables\Filters\SelectFilter::make('created_by')
                    ->label('Created By')
                    ->options(
                        User::query()
                            ->select('created_by')
                            ->distinct()
                            ->pluck('created_by', 'created_by')
                            ->filter() // pastikan tidak null
                            ->toArray()
                    ),
            ])

            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                // ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::auth()->user()?->role === 'admin' || Filament::auth()->user()?->role === 'superadmin';
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->role === 'admin' || Filament::auth()->user()?->role === 'superadmin';
    }

    public static function canView(Model $record): bool
    {
        $user = Filament::auth()->user();
        return $user->role === 'admin' || $user->role === 'superadmin' || $user->id === $record->id;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (! isset($data['updated_by']) || blank($data['updated_by'])) {
            $data['updated_by'] = Filament::auth()->user()?->name ?? 'System';
        }

        // Simpan plain password untuk log/keterangan jika tersedia
        if (isset($data['password'])) {
            $data['password_plain'] = request()->input('data.password');
        }

        return $data;
    }
}
