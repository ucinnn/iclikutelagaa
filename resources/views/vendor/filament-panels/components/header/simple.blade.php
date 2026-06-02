@props([
    'heading' => null,
    'logo' => true,
    'logoUrl' => null,
    'subheading' => null,
])

<header class="fi-simple-header flex flex-col items-center">
   @if ($logo)
        <div class="fi-simple-header-logo flex justify-center">
            @if($logoUrl ?? url('/'))
                <a href="{{ $logoUrl ?? url('/') }}" class="transition-opacity hover:opacity-80">
                    <x-filament-panels::logo />
                </a>
            @else
                <x-filament-panels::logo />
            @endif
        </div>
    @endif

    @if (filled($heading))
        <h1
            class="fi-simple-header-heading text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white"
        >
            {{ $heading }}
        </h1>
    @endif

    @if (filled($subheading))
        <p
            class="fi-simple-header-subheading mt-2 text-center text-sm text-gray-500 dark:text-gray-400"
        >
            {{ $subheading }}
        </p>
    @endif
</header>
