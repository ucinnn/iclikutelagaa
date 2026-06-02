@php
    use Filament\Facades\Filament;
@endphp

<x-filament-panels::page
    @class([
        'fi-page-edit-user-profile',
        'fi-user-profile-' . Filament::auth()?->user()?->getKey(),
    ])
>
    @capture($form)
        <x-filament-panels::form
            id="form"
            :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
            wire:submit="save"
        >
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
    @endcapture

    {{ $form() }}

</x-filament-panels::page>
