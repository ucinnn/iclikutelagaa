<?php

namespace App\Filament\Resources\FAQResource\Pages;

use App\Filament\Resources\FAQResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFAQ extends CreateRecord
{
    protected static string $resource = FAQResource::class;
    protected function getRedirectUrl(): string
    {
        // Redirect to the list page (index) of the resource
        return static::$resource::getUrl('index');
    }
}
