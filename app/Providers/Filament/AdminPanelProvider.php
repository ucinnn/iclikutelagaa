<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

use App\Http\Middleware\EnsureHasValidRole;
use App\Http\Middleware\CheckModuleAccess;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

use App\Overrides\FilamentLanguageSwitcher\SetLocale;

use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\HealthCheckResults;
use App\Filament\Pages\GoogleAnalyticsDashboard;

use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->authGuard('admin')
            ->profile(\App\Filament\Pages\Auth\EditProfile::class)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth(250)
            ->favicon(fn() => env('APP_LOGO') ? asset(env('APP_LOGO')) : asset('images/logo.png'))
            ->brandLogo(fn() => env('APP_LOGO') ? asset(env('APP_LOGO')) : asset('images/logo.png'))
            ->brandLogoHeight('55px')
            ->brandName('Information Center')
            ->navigationGroups([
                __('news.title_group'),
                __('faq.navigation_group'),
                __('system.title_group'),
            ])
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Neutral,
                'danger' => Color::Red,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'info' => Color::Sky,
                'secondary' => Color::Slate,
                'accent' => Color::Violet,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // discoverPages DIMATIKAN — daftarkan manual agar tidak konflik
            // ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->unsavedChangesAlerts()
            ->pages([
                Dashboard::class,
                GoogleAnalyticsDashboard::class,
                HealthCheckResults::class,
                \App\Filament\Pages\ViewEnv::class,   // halaman custom env kamu
                // CustomViewEnvPage DIHAPUS — sudah digantikan ViewEnv.php
            ])
            ->plugins([
                EasyFooterPlugin::make()
                    ->withGithub(showLogo: true, showUrl: true),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->usingPage(HealthCheckResults::class),
                FilamentLanguageSwitcherPlugin::make()
                    ->locales([
                        ['code' => 'id', 'name' => 'Indonesia [id]', 'flag' => 'id'],
                    ]),
                FilamentEnvEditorPlugin::make()
                    ->hideKeys(
                        'APP_NAME',
                        'APP_KEY',
                        'APP_ENV',
                        'APP_DEBUG',
                        'APP_URL',
                        'APP_LOCALE',
                        'APP_FALLBACK_LOCALE',
                        'APP_FAKER_LOCALE',
                        'APP_MAINTENANCE_DRIVER',
                        'ANALYTICS_PROPERTY_ID',
                        'PHP_CLI_SERVER_WORKERS',
                        'LOG_CHANNEL',
                        'LOG_STACK',
                        'LOG_DEPRECATIONS_CHANNEL',
                        'LOG_LEVEL',
                        'DB_CONNECTION',
                        'DB_HOST',
                        'DB_PORT',
                        'DB_DATABASE',
                        'DB_USERNAME',
                        'DB_PASSWORD',
                        'SESSION_DRIVER',
                        'SESSION_LIFETIME',
                        'SESSION_ENCRYPT',
                        'SESSION_DOMAIN',
                        'BROADCAST_CONNECTION',
                        'BROADCAST_DRIVER',
                        'FILESYSTEM_DISK',
                        'QUEUE_CONNECTION',
                        'CACHE_STORE',
                        'CACHE_DRIVER',
                        'MEMCACHED_HOST',
                        'SESSION_PATH',
                        'REDIS_CLIENT',
                        'REDIS_HOST',
                        'REDIS_PASSWORD',
                        'REDIS_PORT',
                        'AWS_ACCESS_KEY_ID',
                        'AWS_SECRET_ACCESS_KEY',
                        'AWS_DEFAULT_REGION',
                        'AWS_BUCKET',
                        'AWS_USE_PATH_STYLE_ENDPOINT',
                        'VITE_APP_NAME',
                        'BCRYPT_ROUNDS'
                    )
                    ->authorize(
                        fn(): bool => in_array(Auth::user()?->role, ['superadmin'])
                    )
                    ->viewPage(\App\Filament\Pages\ViewEnv::class)
                    ->navigationGroup(__('system.title_group'))
                    ->navigationLabel('Environment Editor')
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    ->navigationSort(99)
                    ->slug('env-editor'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                // CheckModuleAccess::class,
                EnsureHasValidRole::class,
            ]);
    }
}