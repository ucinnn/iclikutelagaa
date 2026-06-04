<?php

namespace App\Providers;

use App\Models\SocialLink;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use App\View\Components\Layouts\App;
use Filament\Facades\Filament;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

// Middleware: urutan penting, Original dulu baru Custom
use CraftForge\FilamentLanguageSwitcher\Http\Middleware\SetLocale as OriginalSetLocale;
use App\Overrides\FilamentLanguageSwitcher\SetLocale as CustomSetLocale;


use App\Models\News;
use App\Observers\AnnouncementObserver;
use App\Observers\NewsObserver;

use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override middleware milik plugin dengan versi custom kita
        $this->app->bind(OriginalSetLocale::class, CustomSetLocale::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');
        
        News::observe(AnnouncementObserver::class);
        News::observe(NewsObserver::class);

        View::composer('components.layouts.header', function ($view) {
            $view->with([
                'user' => Auth::user(),
                'socialLinks' => SocialLink::all(),
                'brandName' => filament()->getBrandName(),
                'brandLogo' => filament()->getBrandLogo(),
            ]);
        });

        // Registrasi komponen Blade layout utama
        Blade::component('layouts.app', App::class);

        // Pastikan middleware SetLocale bawaan plugin digantikan
        Filament::serving(function () {
            // Pastikan session locale diterapkan saat Filament dijalankan
            if (session()->has('locale')) {
                app()->setLocale(session('locale'));
            }

            // Jika ada request dari plugin switcher yang mengirim "locale" lewat Livewire
            if (request()->has('locale')) {
                $locale = request()->get('locale');
                session()->put('locale', $locale);
                app()->setLocale($locale);
            }
        });

        Filament::registerRenderHook(
            'panels::body.start',
            fn(): string => "<script>
                setInterval(() => window.livewire.emit('refreshNotifications'), 10000);
            </script>"
        );
    }
}
