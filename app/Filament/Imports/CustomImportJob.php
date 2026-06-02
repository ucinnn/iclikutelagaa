<?php

namespace App\Filament\Imports;

use HayderHatem\FilamentExcelImport\Actions\Imports\Jobs\ImportExcel;

class CustomImportJob extends ImportExcel
{
    public function handle(): void
    {
        // Custom pre-processing

        parent::handle();

        // Custom post-processing
    }
}
