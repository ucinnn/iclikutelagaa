<?php

namespace App\Filament\Resources\FAQResource\Pages;

use App\Filament\Resources\FAQResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use Carbon\Carbon;

class ListFAQS extends ListRecords
{
    protected static string $resource = FAQResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ExportAction::make()
                ->label('Export FAQ')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('secondary')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename('faq-' . date('Y-m-d'))
                        ->withColumns([
                            Column::make('no')
                                ->heading('No')
                                ->getStateUsing(function () {
                                    static $no = 0;
                                    return ++$no;
                                }),

                            Column::make('category')
                                ->heading('Kategori'),

                            Column::make('question')
                                ->heading('Pertanyaan'),

                            Column::make('answer')
                                ->heading('Jawaban'),

                            Column::make('created_by')
                                ->heading('Dibuat Oleh'),

                            Column::make('created_at')
                                ->heading('Dibuat')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? Carbon::parse($state)->format('d M Y H:i') : '-'
                                ),
                        ]),
                ]),
        ];
    }
}