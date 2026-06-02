<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PopUpNewsResource\Pages;
use App\Models\PopUpNews;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Malzariey\FilamentLexicalEditor\FilamentLexicalEditor;
use Malzariey\FilamentLexicalEditor\Enums\ToolbarItem;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;


class PopUpNewsResource extends Resource
{
    protected static ?string $model = PopUpNews::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    
    public static function getNavigationLabel(): string
    {
        return __('popup.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('news.title_group');
    }
    protected static ?int $navigationSort = 1;

    // 🔒 Batasi akses berdasarkan role user
    public static function can(string $action, ?Model $record = null): bool
    {
        $user = Filament::auth()->user();
        if (!$user || !isset($user->role)) {
            return false;
        }
        return in_array($user->role, ['superadmin', 'admin', 'author']);
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
        return in_array($user->role, ['superadmin', 'admin', 'author']);
    }

    // 🧩 FORM
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('popup.form.title'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                        fn($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                Forms\Components\TextInput::make('slug')
                    ->label(__('popup.form.slug'))
                    ->disabled()
                    ->dehydrated(),

                Fieldset::make(__('popup.form.fieldset_content'))
                    ->schema([
                        FilamentLexicalEditor::make('content')
                            ->label(__('popup.form.content_label'))
                            ->nullable()
                            ->rules(['max:6000000']) // Maksimal 6000000 karakter
                            ->validationMessages([
                                'max' => __('popup.form.content_validation_max'),
                            ])
                            ->helperText(__('popup.form.content_helper'))
                            ->enabledToolbars([
                                ToolbarItem::UNDO,
                                ToolbarItem::REDO,
                                ToolbarItem::FONT_FAMILY,
                                ToolbarItem::NORMAL,
                                ToolbarItem::H1,
                                ToolbarItem::H2,
                                ToolbarItem::H3,
                                ToolbarItem::H4,
                                ToolbarItem::H5,
                                ToolbarItem::H6,
                                ToolbarItem::BULLET,
                                ToolbarItem::NUMBERED,
                                ToolbarItem::QUOTE,
                                ToolbarItem::CODE,
                                ToolbarItem::FONT_SIZE,
                                ToolbarItem::BOLD,
                                ToolbarItem::ITALIC,
                                ToolbarItem::UNDERLINE,
                                ToolbarItem::ICODE,
                                ToolbarItem::LINK,
                                ToolbarItem::TEXT_COLOR,
                                ToolbarItem::BACKGROUND_COLOR,
                                ToolbarItem::LOWERCASE,
                                ToolbarItem::UPPERCASE,
                                ToolbarItem::CAPITALIZE,
                                ToolbarItem::STRIKETHROUGH,
                                ToolbarItem::SUBSCRIPT,
                                ToolbarItem::SUPERSCRIPT,
                                ToolbarItem::CLEAR,
                                ToolbarItem::LEFT,
                                ToolbarItem::CENTER,
                                ToolbarItem::RIGHT,
                                ToolbarItem::JUSTIFY,
                                ToolbarItem::INDENT,
                                ToolbarItem::OUTDENT,
                                ToolbarItem::HR,
                                ToolbarItem::IMAGE,
                            ]),
                    ]),

                Forms\Components\DateTimePicker::make('start_at')
                    ->label(__('popup.form.start_at'))
                    ->default(now())
                    ->required(),

                Forms\Components\DateTimePicker::make('end_at')
                    ->label(__('popup.form.end_at'))
                    ->nullable()
                    ->hint(__('popup.form.end_at_hint')),

                Forms\Components\FileUpload::make('image')
                    ->label(__('popup.form.image'))
                    ->image()
                    ->directory('popup-news')
                    ->nullable()
                    ->maxSize(16984)
                    ->imagePreviewHeight('150')
                    ->loadingIndicatorPosition('right')
                    ->uploadingMessage(__('popup.form.image_uploading'))
                    ->columnspanfull()
                    ->removeUploadedFileButtonPosition('right'),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('popup.form.is_active'))
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label(__('popup.table.no'))
                    ->rowIndex()
                    ->toggleable(),
                    
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('popup.table.image'))
                    ->size(40)
                    ->square()
                    ->getStateUsing(function ($record) {
                        if (!empty($record->image)) {
                            $imagePath = $record->image;
                
                            if (Storage::exists($imagePath)) {
                                return Storage::url($imagePath);
                            }
                
                            if (Storage::exists('popup-image/' . $imagePath)) {
                                return Storage::url('popup-image/' . $imagePath);
                            }
                
                            if (file_exists(public_path('storage/' . $imagePath))) {
                                return asset('storage/' . $imagePath);
                            }
                
                            // Jika disimpan langsung di public/popup-image/
                            if (file_exists(public_path('popup-image/' . basename($imagePath)))) {
                                return asset('popup-image/' . basename($imagePath));
                            }
                        }
                
                        // Fallback ke logo
                        return asset('images/logo.png');
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('popup.table.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('popup.table.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('start_at')
                    ->label(__('popup.table.start_at'))
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('end_at')
                    ->label(__('popup.table.end_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label(__('popup.table.information'))
                    // ... (logika formatStateUsing Anda tetap sama) ...
                    ->formatStateUsing(function ($state) {
                        $decoded = json_decode($state, true);
                        if (empty($decoded)) {
                            return '-';
                        }
                        $type = $decoded[0]['type'] ?? 'unknown';
                        $data = $decoded[0]['data'] ?? [];
                        if ($type === 'heading') {
                            return "Heading: " . ($data['text'] ?? '');
                        } elseif ($type === 'video') {
                            return "Video (" . ($data['alignment'] ?? '-') . ")";
                        } else {
                            return ucfirst($type);
                        }
                    })
                    ->wrap()
                    ->limit(255)
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => strip_tags($state))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('popup.filter.is_active_label'))
                    ->trueLabel(__('popup.filter.is_active_true'))
                    ->falseLabel(__('popup.filter.is_active_false')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListPopUpNews::route('/'),
            'create' => Pages\CreatePopUpNews::route('/create'),
            'edit'   => Pages\EditPopUpNews::route('/{record}/edit'),
        ];
    }
}