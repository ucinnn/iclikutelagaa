<?php

namespace App\Filament\Pages\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;
use Illuminate\Support\Facades\Artisan;

class EditAction extends Action
{
    private EntryObj $entry;

    public static function getDefaultName(): ?string
    {
        return 'edit';
    }

    public function setEntry(EntryObj $obj): static
    {
        $this->entry = $obj;
        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-c-cog-8-tooth')
            ->hiddenLabel()
            ->color('primary')
            ->size(ActionSize::Small)
            ->outlined()
            ->modalIcon('heroicon-c-cog-8-tooth')
            ->modalHeading(__('filament-env-editor::filament-env-editor.actions.edit.modal.text'));

        $this->form([
            TextInput::make('key')
                ->default(fn() => $this->entry->key)
                ->required()
                ->markAsRequired() // tampilkan tanda bintang (*)
                ->disabled(),      // disabled untuk tampilan, key diambil dari $this->entry
            TextInput::make('value')
                ->default(fn() => $this->entry->getValue())
                ->required()
                ->markAsRequired() // tampilkan tanda bintang (*)
                ->rule('required')
                ->validationMessages([
                    'required' => 'Value tidak boleh kosong.',
                ]),
        ]);

        $this->action(function (array $data, ViewEnv $page) {
            // Validasi manual: value tidak boleh kosong
            if (empty(trim($data['value'] ?? ''))) {
                Notification::make()
                    ->title('Gagal')
                    ->body('Value tidak boleh kosong.')
                    ->danger()
                    ->send();
                return;
            }

            // Ambil key dari $this->entry langsung, bukan dari $data
            EnvEditor::editKey($this->entry->key, $data['value']);

            Artisan::call('config:clear');

            Notification::make()
                ->title('Berhasil')
                ->body("Key `{$this->entry->key}` berhasil diperbarui.")
                ->success()
                ->send();

            redirect()->to($page->getUrl());
        });

        $this->tooltip(function (): string {
            return isset($this->entry)
                ? __('filament-env-editor::filament-env-editor.actions.edit.tooltip', ['name' => $this->entry->key])
                : '';
        });
    }
}