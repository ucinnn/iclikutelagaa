<?php

namespace App\Filament\Resources\PopUpNewsResource\Pages;

use App\Filament\Resources\PopUpNewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPopUpNews extends ListRecords
{
    protected static string $resource = PopUpNewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
