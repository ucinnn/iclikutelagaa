<?php

namespace App\Filament\Widgets;

use App\Models\FAQ;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class FaqStatWidget extends Widget
{
    protected static string $view = 'filament.widgets.faq-stat-widget';

    protected static ?string $pollingInterval = '30s';


    protected static ?int $sort = 5;

    public function getViewData(): array
    {
        $categoryCounts = FAQ::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'totalFaqs' => FAQ::count(),
            'totalCategories' => FAQ::distinct('category')->count('category'),
            'recentFaqs' => FAQ::latest()->limit(5)->get(),
            'categoryCounts' => $categoryCounts,
            'topCategory' => $categoryCounts->first(),
        ];
    }
}
