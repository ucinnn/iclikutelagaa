<x-filament::page>
        {{-- <form wire:submit.prevent="applyFilters" class="space-y-4"> --}}
            {{ $this->filtersForm }}
            {{-- <x-filament::button type="submit">
                Terapkan Filter
            </x-filament::button> --}}
        {{-- </form> --}}

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 mt-6">
        @foreach ($this->getWidgets() as $widget)
            @livewire($widget, ['filters' => $this->filters ?? []], key($widget))
        @endforeach
    </div>
</x-filament::page>
