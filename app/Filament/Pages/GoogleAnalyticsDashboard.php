<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentGoogleAnalytics\Widgets;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;

class GoogleAnalyticsDashboard extends Page
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Google Analytics Dashboard';
    protected static ?string $slug = 'google-analytics-dashboard';
    protected static string $view = 'filament.pages.google-analytics-dashboard';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['superadmin', 'admin', 'author']);
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Time Filter')
                ->schema([
                    DatePicker::make('startDate')
                        ->label('Start Date')
                        ->maxDate(fn(Get $get) => $get('endDate') ?: now()),

                    DatePicker::make('endDate')
                        ->label('End Date')
                        ->minDate(fn(Get $get) => $get('startDate') ?: now())
                        ->maxDate(now()),
                ])
                ->columns(2),
        ]);
    }

    public function applyFilters(): void
    {
        $this->dispatch('$refresh');
    }

    public function getWidgets(): array
    {
        return [
            Widgets\PageViewsWidget::class,
            Widgets\VisitorsWidget::class,
            Widgets\ActiveUsersOneDayWidget::class,
            Widgets\ActiveUsersSevenDayWidget::class,
            Widgets\ActiveUsersTwentyEightDayWidget::class,
            Widgets\SessionsWidget::class,
            Widgets\SessionsByCountryWidget::class,
            Widgets\SessionsDurationWidget::class,
            Widgets\SessionsByDeviceWidget::class,
            Widgets\MostVisitedPagesWidget::class,
            Widgets\TopReferrersListWidget::class,
        ];
    }
}
