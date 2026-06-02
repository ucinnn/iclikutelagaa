@php
    $brandName = filament()->getBrandName();
    $brandLogo = filament()->getBrandLogo();
    $brandLogoHeight = filament()->getBrandLogoHeight() ?? '1.5rem';
    $darkModeBrandLogo = filament()->getDarkModeBrandLogo();
    $hasDarkModeBrandLogo = filled($darkModeBrandLogo);

    $logoStyles = "height: {$brandLogoHeight}";
@endphp

<div class="flex items-center gap-2">
    {{-- Logo default --}}
    @if (filled($brandLogo))
        <img
            src="{{ $brandLogo }}"
            alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
            style="{{ $logoStyles }}"
            class="{{ $hasDarkModeBrandLogo ? 'hidden dark:block' : '' }}"
        />
    @endif

    {{-- Logo dark mode --}}
    @if ($hasDarkModeBrandLogo)
        <img
            src="{{ $darkModeBrandLogo }}"
            alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
            style="{{ $logoStyles }}"
            class="block dark:hidden"
        />
    @endif

    {{-- Brand name --}}
    <span class="text-2xl font-bold text-gray-950 dark:text-white">
        {{ $brandName }}
    </span>
</div>
