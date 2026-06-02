<?php

namespace App\Filament\Resources\WistleBlowingResource\Pages;

use App\Filament\Resources\WistleBlowingResource;
use App\Models\WistleBlowing;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ViewWistleBlowing extends ViewRecord
{
    protected static string $resource = WistleBlowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
            Actions\EditAction::make(),
        ];
    }

    // Override: sembunyikan relasi user jika bukan superadmin
    protected function resolveRecord(int | string $key): WistleBlowing
    {
        $record = parent::resolveRecord($key);

        if (Auth::user()?->role !== 'superadmin') {
            // Ganti relasi user dengan user kosong agar Placeholder tidak bisa baca
            $record->setRelation('user', null);
        }

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (Auth::user()?->role !== 'superadmin') {
            $data['user_id'] = null;
        }

        return $data;
    }
}