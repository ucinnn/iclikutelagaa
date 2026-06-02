<?php

namespace App\Filament\Resources\TagsResource\Pages;

use App\Filament\Resources\TagsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTags extends CreateRecord
{
    protected static string $resource = TagsResource::class;
    protected function getRedirectUrl(): string
    {
        // Redirect to the list page (index) of the resource
        return static::$resource::getUrl('index');
    }
}
