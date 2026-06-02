<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\SurveyWidget;
use App\Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\UserStat;
use App\Filament\Widgets\RecentNewsActivityWidget;
use App\Filament\Widgets\NewsStatWidget;
use App\Filament\Widgets\FaqStatWidget;
use App\Filament\Widgets\HelpdeskWidget;
use App\Filament\Widgets\PopularNewsWidget;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

use App\Filament\Widgets\NewsChartWidget;
use App\Filament\Widgets\PopUpNewsChartWidget;

use Filament\Actions\Action;
use Filament\Notifications\Notification;

use App\Models\User;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    // public function filtersForm(Form $form): Form
    // {
    //     return $form->schema([
    //         Section::make()
    //             ->schema([
    //                 DatePicker::make('startDate')
    //                     ->label('Start Date')
    //                     ->maxDate(fn(Get $get) => $get('endDate') ?: now()),

    //                 DatePicker::make('endDate')
    //                     ->label('End Date')
    //                     ->minDate(fn(Get $get) => $get('startDate') ?: now())
    //                     ->maxDate(now()),
    //             ])
    //             ->columns(3),
    //     ]);
    // }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendAnnouncement')
                ->label(__('dashboard.actions.send_announcement'))
                ->icon('heroicon-o-megaphone')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->label(__('dashboard.actions.title_label')),
                    \Filament\Forms\Components\Textarea::make('body')
                        ->required()
                        ->label(__('dashboard.actions.body_label')),
                ])
                ->action(function (array $data) {
                    $users = User::all();

                    Notification::make()
                        ->title($data['title'])
                        ->body($data['body'])
                        ->icon('heroicon-o-bell')
                        ->iconColor('success')
                        ->sendToDatabase($users);

                    Notification::make()
                        ->title('Pengumuman dengan judul' . $data['title'] ."berhasil dikirimkan")
                        ->body('Pengumuman terkirim ke ', ['count' => $users->count()])
                        ->success()
                        ->send();
                }),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
            FilamentInfoWidget::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            UserStat::class,
            NewsStatWidget::class,
            SurveyWidget::class,
            FaqStatWidget::class,
            PopUpNewsChartWidget::class,
            HelpdeskWidget::class,
            PopularNewsWidget::class,
            RecentNewsActivityWidget::class,
        ];
    }
}