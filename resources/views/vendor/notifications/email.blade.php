<x-mail::message>
{{-- App Logo --}}
<x-slot:header>
    <x-mail::header :url="config('app.url')">
        @props([
            'url' => config('app.url'),
        ])

        <tr>
            <td class="header">
                <a href="{{ $url }}" style="display: inline-block;">
                    {{--
                        Logika ini akan mencoba mengambil logo dari environment variable 'APP_LOGO'.
                        Jika tidak ada, maka akan menggunakan logo fallback dari URL statis.
                    --}}
            <img src="{{ config('app.logo') }}" class="logo" alt="{{ config('app.name') }} Logo" style="height: 60px; max-height: 60px;">                </a>
            </td>
        </tr>
    </x-mail::header>
</x-slot:header>

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Halo!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Salam hangat,')<br>
Admin {{ config('app.name') }}<br>
<a href="mailto:{{ env('APP_SITEMAIL') }}">{{ env('APP_SITEMAIL') }}</a>
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Jika kamu mengalami masalah ketika menekan tombol \":actionText\", salin dan tempel URL berikut\n" .
    'di browser kamu:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
