<?php
namespace App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource;
use App\Filament\Imports\UserImporter;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(UserImporter::class)
                ->label('Import Users')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->maxRows(1000),
            ExportAction::make()
                ->label('Export Users')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('secondary')
                ->exports([
            ExcelExport::make()
                ->fromTable()
                ->modifyQueryUsing(function ($query) {
                    $tableFilters = $this->tableFilters ?? [];
            
                    if (!empty($tableFilters['role']['value'])) {
                        $query->where('role', $tableFilters['role']['value']);
                    }
            
                    if (!empty($tableFilters['created_by']['value'])) {
                        $query->where('created_by', $tableFilters['created_by']['value']);
                    }
            
                    // Hapus select() — biarkan ambil semua kolom
                    return $query;
                })
                ->withFilename('users-' . date('Y-m-d'))
                ->withColumns([
                    Column::make('no')
                    ->heading('No')
                    ->getStateUsing(function () {
                        static $no = 0;
                        $no++;
                        return $no;
                    }),
                    Column::make('NIK')->heading('NIK'),
                    Column::make('name')->heading('Nama'),
                    Column::make('email')->heading('Email'),
                    Column::make('role')->heading('Role'),
                    Column::make('created_by')->heading('Created By'),
                    Column::make('created_at')
                        ->heading('Dibuat')
                        ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d M Y H:i') : '-'),
                ])
                ->only(['no','NIK','name', 'email', 'role', 'created_by', 'created_at']),
            ]),
        ];
    }
}