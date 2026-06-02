<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Filament\Facades\Filament;
use Spatie\Health\Checks\Checks\{
    DatabaseCheck,
    CacheCheck,
    QueueCheck,
    UsedDiskSpaceCheck,
    DebugModeCheck,
    EnvironmentCheck,
    OptimizedAppCheck,
    PingCheck
};

class HealthServiceProvider extends ServiceProvider
{
    /**
     * Tentukan apakah service provider ini seharusnya diregistrasi.
     * Bisa digunakan untuk mematikan provider di local/testing jika tidak diperlukan.
     */
    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Bootstrapping semua health checks.
     */
    public function boot(): void
    {
        Health::checks([
            DatabaseCheck::new()->name('Database Connection'),
            CacheCheck::new()->name('Cache System'),
            QueueCheck::new()->name('Queue Jobs'),

            UsedDiskSpaceCheck::new()
                ->name('Disk Usage')
                ->warnWhenUsedSpaceIsAbovePercentage(80)
                ->failWhenUsedSpaceIsAbovePercentage(90),

            DebugModeCheck::new()->name('Debug Mode'),

            EnvironmentCheck::new()
                ->expectEnvironment('production')
                ->name('Environment Mode'),

            OptimizedAppCheck::new()->name('App Optimization'),

            PingCheck::new()
                ->url('https://www.google.com')
                ->name('External: Google Connection')
                ->timeout(5)
                ->failureMessage('Unable to reach Google'),

            PingCheck::new()
                ->url(config('app.url'))
                ->name('Internal: Application URL')
                ->timeout(5)
                ->failureMessage('Main site is not responding'),
        ]);
    }
}
