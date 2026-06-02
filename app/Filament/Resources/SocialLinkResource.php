<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialLinkResource\Pages;
use App\Models\SocialLink;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;
    protected static ?string $navigationIcon = 'heroicon-o-share';

    public static function getNavigationGroup(): ?string
    {
        return __('faq.navigation_group');
    }

    public static function canDelete(Model $record): bool
    {
        $user = Filament::auth()->user();

        return in_array($user->role, ['admin', 'superadmin']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::auth()->user()?->role === 'admin' || Filament::auth()->user()?->role === 'superadmin';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('platform')
                    ->label('Platform')
                    ->options([
                        // Major Social Media
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter (X)',
                        'instagram' => 'Instagram',
                        'tiktok' => 'TikTok',
                        'youtube' => 'YouTube',
                        'threads' => 'Threads',
                        'snapchat' => 'Snapchat',
                        'pinterest' => 'Pinterest',
                        'reddit' => 'Reddit',

                        // Communities & Chat
                        'whatsapp' => 'WhatsApp',
                        'telegram' => 'Telegram',
                        'discord' => 'Discord',
                        'line' => 'LINE',
                        'messenger' => 'Messenger',

                        // Professional & Portfolio
                        'linkedin' => 'LinkedIn',
                        'github' => 'GitHub',
                        'gitlab' => 'GitLab',
                        'dribbble' => 'Dribbble',
                        'behance' => 'Behance',
                        'medium' => 'Medium',
                        'codepen' => 'CodePen',

                        // Video & Music
                        'vimeo' => 'Vimeo',
                        'twitch' => 'Twitch',
                        'spotify' => 'Spotify',
                        'soundcloud' => 'SoundCloud',

                        // E-Commerce & Platforms
                        'shopify' => 'Shopify',
                        'amazon' => 'Amazon',
                        'ebay' => 'eBay',

                        // Others
                        'wechat' => 'WeChat',
                        'tumblr' => 'Tumblr',
                        'quora' => 'Quora',
                        'rss' => 'RSS Feed',
                        'website' => 'Website / Other',
                    ])
                    ->searchable()
                    ->required()
                    ->preload()
                    ->hint('Select a social media or related platform'),
                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->helperText('Contoh: https://facebook.com/xxxx')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('platform')->label('Platform')->sortable(),
                Tables\Columns\TextColumn::make('url')->label('URL')->wrap(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialLinks::route('/'),
            'create' => Pages\CreateSocialLink::route('/create'),
            'edit' => Pages\EditSocialLink::route('/{record}/edit'),
        ];
    }
}
