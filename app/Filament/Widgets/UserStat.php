<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Filament\Facades\Filament;

class UserStat extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        $user = Filament::auth()->user();

        if (!$user || !isset($user->role)) {
            return false;
        }

        // Widget hanya ditampilkan untuk peran superadmin & admin
        return in_array($user->role, ['superadmin', 'admin']);
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('dashboard.total_users'), User::count())
                ->description(__('dashboard.total_users_desc'))
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make(__('dashboard.admins'), User::where('role', 'admin')->count())
                ->description(__('dashboard.admins_desc'))
                ->icon('heroicon-o-shield-check')
                ->color('primary'),

            Stat::make(__('dashboard.authors'), User::where('role', 'author')->count())
                ->description(__('dashboard.authors_desc'))
                ->icon('heroicon-o-pencil-square')
                ->color('primary'),

            Stat::make(__('dashboard.regular_users'), User::where('role', 'user')->count())
                ->description(__('dashboard.regular_users_desc'))
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
