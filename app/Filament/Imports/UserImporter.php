<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nik')
                ->label('NIK')
                ->rules(['required', 'unique:users,NIK'])
                ->example('LTG0000150'),

            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('John Doe'),

            ImportColumn::make('role')
                ->label('Role')
                ->requiredMapping()
                ->rules(['required', 'in:admin,user,manager'])
                ->example('user'),

            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'unique:users,email'])
                ->example('user@example.com'),

            ImportColumn::make('password')
                ->label('Password')
                ->requiredMapping()
                ->rules(['required', 'min:6'])
                ->example('password123'),
        ];
    }

    public function resolveRecord(): ?User
    {
        // Cari user berdasarkan email atau buat baru
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import user selesai! ' . number_format($import->successful_rows) . ' baris berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diimport.';
        }

        return $body;
    }

    protected function beforeFill(): void
    {
        // Hash password sebelum disimpan
        if (isset($this->data['password'])) {
            $this->data['password'] = Hash::make($this->data['password']);
        }
    }
}
