<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FAQResource\Pages;
use App\Models\FAQ;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;

class FAQResource extends Resource
{
    protected static ?string $model = FAQ::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'FAQ';

    public static function getNavigationGroup(): ?string
    {
        return __('faq.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('faq.navigation_label');
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Filament::auth()->user()?->role === 'admin' || Filament::auth()->user()?->role === 'superadmin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                    ->label(__('faq.fields.category'))
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->options([
                        'general' => __('faq.categories.general'),
                        'content' => __('faq.categories.content'),
                        'technical' => __('faq.categories.technical'),
                        'privacy' => __('faq.categories.privacy'),
                    ])
                    ->createOptionForm([
                        Forms\Components\TextInput::make('category')
                            ->label(__('faq.fields.new_category_key'))
                            ->helperText(__('Gunakan huruf kecil dengan underscore (misal: kategori_baru)'))
                            ->required()
                            ->regex('/^[a-z_]+$/')
                            ->validationMessages([
                                'regex' => __('Category key must be lowercase letters and underscores only.'),
                            ]),
                        Forms\Components\TextInput::make('label')
                            ->label(__('faq.fields.new_category_label'))
                            ->required()
                            ->helperText(__('Tampilan nama kategori')),
                    ])
                    ->helperText(__('Pilih kategori atau buat baru'))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('question')
                    ->label(__('faq.fields.question'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('faq.search_placeholder'))
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('answer')
                    ->label(__('faq.fields.answer'))
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'link',
                        'bulletList',
                        'orderedList',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->placeholder(__('faq.fields.answer'))
                    ->columnSpanFull(),

                Hidden::make('created_by')
                    ->dehydrated()
                    ->default(fn() => Filament::auth()->user()?->name ?? 'System'),

                Hidden::make('updated_by')
                    ->dehydrated()
                    ->default(fn() => Filament::auth()->user()?->name ?? 'System'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label(__('faq.table.no'))
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('faq.table.category'))
                    ->formatStateUsing(fn(string $state) => __(
                        'faq.categories.' . $state,
                        ['default' => ucwords(str_replace('_', ' ', $state))]
                    ))
                    ->badge(),

                Tables\Columns\TextColumn::make('question')
                    ->label(__('faq.table.question'))
                    ->limit(255)
                    ->wrap(),

                Tables\Columns\TextColumn::make('answer')
                    ->label(__('faq.table.answer'))
                    ->limit(255)
                    ->formatStateUsing(fn($state) => strip_tags($state))
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_by')
                    ->label(__('faq.table.created_by')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('faq.table.created_at'))
                    ->dateTime(),

                Tables\Columns\TextColumn::make('updated_by')
                    ->label(__('faq.table.updated_by')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('faq.table.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_by')
                    ->label(__('faq.filters.created_by'))
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFAQS::route('/'),
            'create' => Pages\CreateFAQ::route('/create'),
            'edit' => Pages\EditFAQ::route('/{record}/edit'),
        ];
    }
}
