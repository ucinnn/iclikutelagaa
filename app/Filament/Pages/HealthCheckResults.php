<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\Support\Htmlable;
use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;

class HealthCheckResults extends BaseHealthCheckResults
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public function getHeading(): string|Htmlable
    {
        return __('Application Health');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('system.title_group');
    }
}
