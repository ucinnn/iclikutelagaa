<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Widgets\Widget;

class PopularNewsWidget extends Widget
{
    protected static string $view = 'filament.widgets.popular-news-widget';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'popularNews' => News::where('status', 'published')
                ->orderBy('views', 'desc')
                ->limit(10)
                ->get(),
            'totalNews' => News::count(),
        ];
    }
}
