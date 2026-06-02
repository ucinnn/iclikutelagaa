<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;
        protected function getRedirectUrl(): string
    {
        // Using getUrl() from the resource for a consistent URL.
        return static::getResource()::getUrl('index');
    }
}
