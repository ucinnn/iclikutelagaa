<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Widgets\Widget;

class RecentNewsActivityWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-news-activity';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getRecentNews()
    {
        return News::with('category')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($news) {
                return [
                    'id' => $news->id,
                    'title' => $news->title,
                    'author' => $news->author,
                    'status' => $news->status,
                    'views' => $news->views,
                    'featured' => $news->featured,
                    'created_at' => $news->created_at->diffForHumans(),
                    'thumbnail' => $news->thumbnail,
                ];
            });
    }
}
