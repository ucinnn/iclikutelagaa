<?php

namespace App\Filament\Resources\WistleBlowingResource\Pages;

use App\Filament\Resources\WistleBlowingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWistleBlowing extends EditRecord
{
    protected static string $resource = WistleBlowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
             
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
