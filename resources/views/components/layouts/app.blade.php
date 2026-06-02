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

        <!-- Bagian Atas (Profil & Sosial) -->
    <div class="w-full hidden lg:flex bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200/60 shadow-sm">
        <div class="w-full mx-auto px-8 flex justify-between items-center h-14 overflow-hidden">
            <!-- User Info -->
            <div class="flex items-center space-x-3 text-sm text-slate-700 truncate">
                @auth
                    @php
                        $name = Auth::user()->name ?? 'User';
                        $words = explode(' ', trim($name));
                        $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? ($words[0] ?? ''), 0, 1));
                    @endphp

                    @if(Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}"
                            alt="User Avatar"
                            class="w-9 h-9 rounded-full border-2 border-white shadow-md object-cover flex-shrink-0 ring-2 ring-slate-200/50">
                    @else
                        <div class="w-9 h-9 flex items-center justify-center rounded-full border-2 border-white shadow-md bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 text-white text-xs font-semibold ring-2 ring-slate-200/50">
                            {{ $initials }}
                        </div>
                    @endif

                    <div class="flex flex-col leading-tight truncate">
                        <span class="font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-slate-500 truncate font-medium">{{ Auth::user()->NIK }}</span>
                    </div>
                    <a href="{{ route('edit-profile') }}"
                        class="ml-3 px-4 py-1.5 text-xs font-medium bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap {{ request()->routeIs('edit-profile') ? 'from-blue-700 to-blue-800 shadow-md' : '' }}">
                        Edit Profil
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium hover:underline transition-colors duration-200">Login</a>
                @endauth
            </div>


            <!-- Social Links -->
            <div class="flex items-center space-x-4 text-slate-600 flex-shrink-0">

                @php
                    $socialLinks = \App\Models\SocialLink::all();
                @endphp

                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 hover:scale-110"
                    title="{{ ucfirst($link->platform) }}">
                    @switch(strtolower($link->platform))
                                        {{-- Sosial Media Umum --}}
                                            @case('facebook') <i class="fab fa-facebook-f text-sm"></i> @break
                                            @case('twitter') <i class="fab fa-twitter text-sm"></i> @break
                                            @case('x') <i class="fab fa-x-twitter text-sm"></i> @break
                                            @case('instagram') <i class="fab fa-instagram text-sm"></i> @break
                                            @case('tiktok') <i class="fab fa-tiktok text-sm"></i> @break
                                            @case('youtube') <i class="fab fa-youtube text-sm"></i> @break
                                            @case('threads') <i class="fab fa-threads text-sm"></i> @break
                                            @case('snapchat') <i class="fab fa-snapchat-ghost text-sm"></i> @break
                                            @case('pinterest') <i class="fab fa-pinterest-p text-sm"></i> @break
                                            @case('reddit') <i class="fab fa-reddit-alien text-sm"></i> @break

                                            {{-- Komunitas dan Chat --}}
                                            @case('whatsapp') <i class="fab fa-whatsapp text-sm"></i> @break
                                            @case('telegram') <i class="fab fa-telegram-plane text-sm"></i> @break
                                            @case('discord') <i class="fab fa-discord text-sm"></i> @break
                                            @case('line') <i class="fab fa-line text-sm"></i> @break
                                            @case('messenger') <i class="fab fa-facebook-messenger text-sm"></i> @break

                                            {{-- Profesional dan Portofolio --}}
                                            @case('linkedin') <i class="fab fa-linkedin-in text-sm"></i> @break
                                            @case('github') <i class="fab fa-github text-sm"></i> @break
                                            @case('gitlab') <i class="fab fa-gitlab text-sm"></i> @break
                                            @case('dribbble') <i class="fab fa-dribbble text-sm"></i> @break
                                            @case('behance') <i class="fab fa-behance text-sm"></i> @break
                                            @case('medium') <i class="fab fa-medium-m text-sm"></i> @break
                                            @case('codepen') <i class="fab fa-codepen text-sm"></i> @break

                                            {{-- Video & Musik --}}
                                            @case('vimeo') <i class="fab fa-vimeo-v text-sm"></i> @break
                                            @case('twitch') <i class="fab fa-twitch text-sm"></i> @break
                                            @case('spotify') <i class="fab fa-spotify text-sm"></i> @break
                                            @case('soundcloud') <i class="fab fa-soundcloud text-sm"></i> @break

                                            {{-- E-Commerce & Platform --}}
                                            @case('shopify') <i class="fab fa-shopify text-sm"></i> @break
                                            @case('amazon') <i class="fab fa-amazon text-sm"></i> @break
                                            @case('ebay') <i class="fab fa-ebay text-sm"></i> @break

                                            {{-- Regional / Lain-lain --}}
                                            @case('wechat') <i class="fab fa-weixin text-sm"></i> @break
                                            @case('tumblr') <i class="fab fa-tumblr text-sm"></i> @break
                                            @case('quora') <i class="fab fa-quora text-sm"></i> @break
                                            @case('rss') <i class="fas fa-rss text-sm"></i> @break

                                            {{-- Default --}}
                                            @default <i class="fas fa-globe text-sm"></i>
                                    @endswitch
                    </a>
                @endforeach
            </div>
            {{-- <x-announcement-bell /> --}}
            <livewire:announcement-list/>
        </div>
    </div>

    {{-- STICKY HEADER --}}
    <header class="bg-white border-b h-20 md:h-28 lg:h-36 border-gray-200 shadow-md transition-all duration-300 ease-in-out">
        @include('components.layouts.header')
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
        <x-layouts.footer />
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
