<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\ActionSize;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class EditAction extends \Filament\Forms\Components\Actions\Action
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

        $this->icon('heroicon-o-pencil');
        $this->modalIcon('heroicon-o-pencil');
        $this->hiddenLabel();
        $this->color('primary');

        $this->form([
            TextInput::make('key')
                ->label('Key')
                ->default(fn () => $this->entry->key)
                ->required()
                ->readOnly(), // tetap terkirim tapi tidak bisa diubah

            TextInput::make('value')
                ->label('Value')
                ->default(fn () => $this->entry->getValue())
                ->nullable(),
        ]);

        $this->action(function (array $data, ViewEnv $page) {

            $key = $data['key'] ?? null;
            $value = $data['value'] ?? '';

            if (!$key) {
                $page->notify('danger', 'Environment key tidak ditemukan.');
                return;
            }

            try {

                EnvEditor::editKey($key, $value);
                EnvEditor::save();

                $page->dispatch('$refresh');

                $page->notify('success', 'Environment variable berhasil diperbarui.');

            } catch (\Throwable $e) {

                $page->notify('danger', 'Update gagal: ' . $e->getMessage());
            }
        });

        $this->size(ActionSize::Small);
        $this->outlined();

        $this->modalHeading(
            __('filament-env-editor::filament-env-editor.actions.edit.modal.text')
        );

        $this->tooltip(fn (): string =>
            __('filament-env-editor::filament-env-editor.actions.edit.tooltip', [
                'name' => $this->entry->key
            ])
        );
    }
}