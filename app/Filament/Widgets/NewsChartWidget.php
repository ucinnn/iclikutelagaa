<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class NewsChartWidget extends ChartWidget
{
    public function getHeading(): string
    {
        return __('dashboard.news-chart-widget.heading');
    }
    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $published = News::where('status', 'published')->count();
        $draft = News::where('status', 'draft')->count();
        $scheduled = News::where('status', 'schedule')->count();

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.news.news'), // Gunakan translasi
                    'data' => [$published, $draft, $scheduled],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // green for published
                        'rgb(234, 179, 8)',   // yellow for draft
                        'rgb(59, 130, 246)',  // blue for scheduled
                    ],
                ],
            ],
            'labels' => [
                __('dashboard.news.published'),
                __('dashboard.news.draft'),
                __('dashboard.news.scheduled'),
            ],
        ];
    }


    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
