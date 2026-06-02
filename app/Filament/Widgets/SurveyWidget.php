<?php

namespace App\Filament\Widgets;

use App\Models\Survey;
use Filament\Widgets\Widget;

class SurveyWidget extends Widget
{
    protected static string $view = 'filament.widgets.survey-widget';

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        // Get active surveys only
        $activeSurveys = Survey::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($survey) {
                return [
                    'id' => $survey->id,
                    'title' => $survey->title,
                    'description' => $survey->description,
                    'link' => $survey->link,
                    'icon' => $survey->icon,
                    'domain' => parse_url($survey->link, PHP_URL_HOST) ?? $survey->link,
                    'created_at_human' => $survey->created_at->diffForHumans(),
                ];
            });

        // Statistics
        $totalSurveys = Survey::count();
        $activeSurveysCount = Survey::where('is_active', true)->count();
        $inactiveSurveysCount = Survey::where('is_active', false)->count();
        $createdToday = Survey::whereDate('created_at', today())->count();

        return [
            'activeSurveys' => $activeSurveys,
            'totalSurveys' => $totalSurveys,
            'activeSurveysCount' => $activeSurveysCount,
            'inactiveSurveysCount' => $inactiveSurveysCount,
            'createdToday' => $createdToday,
        ];
    }
}
