<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationWidget extends Widget
{
    protected static string $view = 'filament.widgets.notifications';

    protected static ?int $sort = -1; // agar muncul di kanan atas
}
