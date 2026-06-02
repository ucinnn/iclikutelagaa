<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\UserImporter;

class FilamentPluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \bezhansalleh\FilamentGoogleAnalytics\FilamentGoogleAnalytics::class,
            \App\Filament\Pages\GoogleAnalyticsDashboard::class
        );
    }

    public function boot(): void {}
}
