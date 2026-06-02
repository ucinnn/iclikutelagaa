<?php

namespace App\Filament\Resources\PopUpNewsResource\Pages;

use App\Filament\Resources\PopUpNewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPopUpNews extends EditRecord
{
    protected static string $resource = PopUpNewsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
