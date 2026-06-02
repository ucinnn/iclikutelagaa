<?php
namespace App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->label('Export Survey')
                ->color('secondary')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename('survey-' . date('Y-m-d'))
                        ->withColumns([
                            Column::make('title')->heading('Judul'),
                            Column::make('link')->heading('Link'),
                            Column::make('icon')->heading('Icon'),
                            Column::make('is_active')
                                ->heading('Aktif')
                                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),
                            Column::make('created_at')
                                ->heading('Dibuat')
                                ->formatStateUsing(fn ($state) => $state?->format('d M Y H:i')),
                        ]),
                ]),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}