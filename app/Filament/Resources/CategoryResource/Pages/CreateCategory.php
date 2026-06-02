<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Override the default redirect URL after creation.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        // Redirect to the list page (index) of the resource
        return static::$resource::getUrl('index');
    }
}
