<?php

namespace App\Filament\Widgets;

use App\Models\PopUpNews;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopUpNewsChartWidget extends ChartWidget
{
    public function getHeading(): string
    {
        return __('dashboard.popup-news-chart-widget.heading');
    }
    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $published = PopUpNews::where('is_active', 'true')->count();
        $draft = PopUpNews::where('is_active', 'false')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Berita',
                    'data' => [$published, $draft],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // green for published
                        'rgb(234, 179, 8)',   // yellow for draft
                    ],
                ],
            ],
            'labels' => ['Published', 'Draft'],
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
