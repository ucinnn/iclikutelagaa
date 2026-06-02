<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class FilamentInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.filament-info-widget';

    protected static ?int $sort = -1; // opsional: tampilkan di urutan atas

    protected static ?string $heading = 'Akun Pengguna';

    // protected int|string|array $columnSpan = 'full'; // agar full width (opsional)
}
