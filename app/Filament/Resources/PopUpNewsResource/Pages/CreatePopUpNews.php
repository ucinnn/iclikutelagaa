<?php

namespace App\Filament\Resources\PopUpNewsResource\Pages;

use App\Filament\Resources\PopUpNewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePopUpNews extends CreateRecord
{
    protected static string $resource = PopUpNewsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
