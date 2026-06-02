<x-filament-panels::page.simple>
    {{-- Jika pendaftaran diaktifkan --}}
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{-- Form Login --}}
    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        {{-- Tombol Aksi (Login, dsb) --}}
        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
        <!-- Back to Login Link -->
        <div class="text-center">
            <a href="{{ route('loginpage') }}" class="text-[#F2B300] text-sm font-bold hover:text-[#D68B00] transition-colors">
                ← Kembali ke halaman sebelumnya
            </a>
        </div>
</x-filament-panels::page.simple>
