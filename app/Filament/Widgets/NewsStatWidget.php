<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsStatWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalNews = News::count();
        $publishedNews = News::where('status', 'published')->count();
        $draftNews = News::where('status', 'draft')->count();
        $scheduledNews = News::where('status', 'schedule')->count();
        $featuredNews = News::where('featured', true)->count();
        $totalViews = News::sum('views');

        $newsThisMonth = News::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $newsLastMonth = News::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $monthlyTrend = $newsLastMonth > 0
            ? (($newsThisMonth - $newsLastMonth) / $newsLastMonth) * 100
            : 0;

        return [
            Stat::make(__('dashboard.news.total_news'), $totalNews)
                ->description(__('dashboard.news.all_news'))
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make(__('dashboard.news.published_news'), $publishedNews)
                ->description(__(
                    'dashboard.news.draft_scheduled',
                    ['draft' => $draftNews, 'scheduled' => $scheduledNews]
                ))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(__('dashboard.news.this_month_news'), $newsThisMonth)
                ->description(
                    $monthlyTrend >= 0
                        ? __('dashboard.news.trend_up', ['value' => number_format(abs($monthlyTrend), 1)])
                        : __('dashboard.news.trend_down', ['value' => number_format(abs($monthlyTrend), 1)])
                )
                ->descriptionIcon($monthlyTrend >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($monthlyTrend >= 0 ? 'success' : 'danger')
                ->chart(array_reverse($this->getLastSevenDaysCount())),

            Stat::make(__('dashboard.news.featured_news'), $featuredNews)
                ->description(__('dashboard.news.featured_description'))
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }

    private function getLastSevenDaysCount(): array
    {
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $counts[] = News::whereDate('created_at', $date)->count();
        }
        return $counts;
    }
}
