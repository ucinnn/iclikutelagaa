<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ExportAction::make()
                ->label('Export Berita')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('secondary')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename('news-' . date('Y-m-d'))
                        ->withColumns([
                            Column::make('no')
                                ->heading('No')
                                ->getStateUsing(function () {
                                    static $no = 0;
                                    return ++$no;
                                }),

                            Column::make('title')
                                ->heading('Judul'),

                            Column::make('author.name')
                                ->heading('Penulis'),

                            Column::make('status')
                                ->heading('Status')
                                ->formatStateUsing(fn ($state) => match ($state) {
                                    'published' => 'Published',
                                    'draft' => 'Draft',
                                    'scheduled' => 'Scheduled',
                                    default => $state,
                                }),

                            Column::make('views')
                                ->heading('Dilihat'),

                            Column::make('created_at')
                                ->heading('Dibuat')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? \Carbon\Carbon::parse($state)->format('d M Y H:i') : '-'
                                ),

                            Column::make('updated_at')
                                ->heading('Diperbarui')
                                ->formatStateUsing(fn ($state) =>
                                    $state ? \Carbon\Carbon::parse($state)->format('d M Y H:i') : '-'
                                ),
                        ]),
                ]),
        ];
    }
}