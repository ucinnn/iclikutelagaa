<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Dynamic Title --}}
    @php
        $pageTitle = trim(strip_tags(
            ($livewire ?? null)?->getTitle()
            ?? View::getSections()['pageTitle'] ?? ''
        ));
        $brandName = trim(strip_tags(filament()->getBrandName() ?? config('app.name')));
    @endphp

    <title>{{ $pageTitle ? "{$pageTitle} - {$brandName}" : $brandName }}</title>

    {{-- Dynamic Favicon --}}
    @php
        $favicon = env('APP_LOGO')
            ? asset(env('APP_LOGO'))
            : (file_exists(public_path('public/images/logo.png'))
                ? asset('vendor/filament/filament-logo.svg')
                : asset('favicon.ico'));
    @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}">

    {{-- Tailwind CSS & Icon Fonts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- App Styles --}}
    @vite('resources/css/app.css')
    @stack('styles')


    <style>
        /* ========================================
           RESET & BASE STYLES
           ======================================== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            scroll-behavior: smooth;
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            color: #111827;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* ========================================
           LAYOUT STRUCTURE
           ======================================== */
        main {
            flex: 1 0 auto;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
            padding-top: 0;
        }

        footer {
            flex-shrink: 0;
            width: 100%;
            margin-top: auto;
        }

        /* ========================================
           STICKY HEADER - SIMPLE & RELIABLE
           ======================================== */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            width: 100%;
              will-change: transform;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }


        /* Fallback untuk browser yang tidak support sticky */
        @supports not (position: sticky) {
            header {
                position: fixed;
                top: 0;
            }

            body {
                padding-top: 60px; /* Sesuaikan dengan tinggi header */
            }
        }

        /* ========================================
           TRENDING BAR STICKY
           ======================================== */
        #trending-bar {
            position: sticky;
            top: 45px; /* sesuaikan tinggi header kamu */
            z-index: 40;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            width: 100%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
              -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        /* ========================================
           NEWS TICKER HORIZONTAL ANIMATION
           ======================================== */
        .ticker-wrapper {
            overflow: hidden;
            position: relative;
            height: 2rem;
            display: flex;
            align-items: center;
        }

        .ticker-content {
            display: flex;
            gap: 3rem;
            animation: scroll-left 40s linear infinite;
            padding-left: 100%;
        }

        .ticker-item {
            display: inline-block;
            padding-right: 3rem;
            white-space: nowrap;
        }

        .ticker-wrapper:hover .ticker-content {
            animation-play-state: paused;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @media (max-width: 640px) {
            .ticker-content {
                animation-duration: 30s;
            }
        }

        /* ========================================
           CONTAINER RESPONSIVE
           ======================================== */
        .container {
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        /* ========================================
           IMAGE RESPONSIVE
           ======================================== */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* ========================================
           BACK TO TOP BUTTON
           ======================================== */
        #myBtn {
            transition: all 0.3s ease;
            transform: scale(0);
        }

        #myBtn:not(.hidden) {
            transform: scale(1);
        }

        /* ========================================
           PREVENT HORIZONTAL SCROLL
           ======================================== */
        body, html {
            overflow-x: hidden;
            max-width: 100vw;
            position: relative;
        }

        /* ========================================
           MOBILE OPTIMIZATIONS
           ======================================== */
        @media (max-width: 639px) {
            html {
                font-size: 14px;
            }

            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }

            /* kalau header lebih tinggi, naikkan nilainya */
        @media (max-width: 768px) {
            #trending-bar {
                top: 56px;
            }
        }

        /* ========================================
           PRINT STYLES
           ======================================== */
        @media print {
            header, footer, #myBtn, #trending-bar {
                display: none;
            }

            main {
                padding-top: 0 !important;
            }
        }
    </style>
</head>

<body class="text-gray-900">
    @if (config('app.env') === 'production')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_MEASUREMENT_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GOOGLE_MEASUREMENT_ID') }}');
    </script>
    @endif

    {{-- STICKY HEADER --}}
    <header class="bg-white border-b h-20 md:h-28 lg:h-36 border-gray-200 shadow-md transition-all duration-300 ease-in-out">
        @include('components.layouts.headeruser')
    </header>


    {{-- MAIN CONTENT --}}
    <main>
        <x-layouts.main>
            {{ $slot ?? '' }}
            @yield('content')
        </x-layouts.main>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-white mt-auto">
        <x-layouts.footeruser />
    </footer>

    {{-- BACK TO TOP BUTTON --}}
    <div id="myBtn" class="fixed bottom-5 right-5 z-50 hidden cursor-pointer">
        <button class="w-12 h-12 bg-red-600 text-white flex items-center justify-center rounded-full shadow-lg hover:bg-red-700 transition-all duration-300 hover:scale-110 active:scale-95">
            <i class="fas fa-angle-up text-xl"></i>
        </button>
    </div>

    @vite('resources/js/app.js')
    @stack('scripts')

    <script>
        // ========================================
        // SIMPLE & RELIABLE BACK TO TOP
        // ========================================
        document.addEventListener('DOMContentLoaded', () => {
            const myBtn = document.getElementById('myBtn');

            if (myBtn) {
                // Back to top visibility
                window.addEventListener('scroll', () => {
                    myBtn.classList.toggle('hidden', window.scrollY < 300);
                }, { passive: true });

                // Scroll to top on click
                myBtn.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });

        // ========================================
        // PREVENT HORIZONTAL SCROLL
        // ========================================
        (function() {
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if (window.scrollX !== 0) {
                        window.scrollTo(0, window.scrollY);
                    }
                }, 10);
            }, { passive: true });
        })();

        // ========================================
        // TOUCH DEVICE OPTIMIZATIONS
        // ========================================
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
        }
    </script>
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
