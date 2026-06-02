<?php

namespace App\Filament\Resources\WistleBlowingResource\Pages;

use App\Exports\WistleBlowingExport;
use App\Filament\Resources\WistleBlowingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListWistleBlowings extends ListRecords
{
    protected static string $resource = WistleBlowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('secondary')
                ->action(function () {
                    // Ambil query dengan semua filter aktif (status, kategori, rentang tanggal, search)
                    $query = $this->getFilteredTableQuery();

                    $records = $query->with('user')->get();

                    // Buat nama file mencerminkan filter aktif
                    $filters     = $this->getTableFilterState('created_at') ?? [];
                    $from        = $filters['from']  ?? null;
                    $until       = $filters['until'] ?? null;

                    $suffix = match(true) {
                        $from && $until => '_' . \Carbon\Carbon::parse($from)->format('Ymd') . '-' . \Carbon\Carbon::parse($until)->format('Ymd'),
                        $from           => '_dari-' . \Carbon\Carbon::parse($from)->format('Ymd'),
                        $until          => '_sampai-' . \Carbon\Carbon::parse($until)->format('Ymd'),
                        default         => '_' . now()->format('Ymd-His'),
                    };

                    return Excel::download(
                        new WistleBlowingExport($records),
                        'laporan-whistleblowing' . $suffix . '.xlsx'
                    );
                }),
        ];
    }
}