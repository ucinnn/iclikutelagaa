<!DOCTYPE html>
<html lang="en">
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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'bg-main': '#0A0A0A',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                },
            },
        };
    </script>

    <!-- Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .dark .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
        
        /* Glass Effect */
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        
        .dark .glass-effect {
            background-color: rgba(17, 24, 39, 0.75);
            border: 1px solid rgba(75, 85, 99, 0.3);
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        .float-animation {
            animation: float 5s ease-in-out infinite;
        }
    </style>

    <script>
        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-bg-main antialiased transition-colors duration-300 overflow-x-hidden">

    <!-- Decorative Background Elements (Smaller) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 -left-4 w-56 h-56 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-70 animate-pulse-slow"></div>
        <div class="absolute top-0 -right-4 w-56 h-56 bg-indigo-300 dark:bg-indigo-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-70 animate-pulse-slow animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-56 h-56 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-70 animate-pulse-slow animation-delay-4000"></div>
    </div>

    <!-- Theme & Language Toggle Buttons -->
    <div class="fixed top-4 right-4 z-50 flex gap-2 animate-fade-in">
        <!-- Language Toggle -->
        <button id="lang-toggle" type="button"
            class="glass-effect text-gray-700 dark:text-gray-200 hover:bg-white/90 dark:hover:bg-gray-800/90 focus:outline-none focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-800 rounded-full text-sm px-3 py-2 transition-all duration-300 shadow-lg font-semibold">
            <span id="lang-text">ID</span>
        </button>
        
        <!-- Theme Toggle -->
        <button id="theme-toggle" type="button"
            class="glass-effect text-gray-700 dark:text-gray-200 hover:bg-white/90 dark:hover:bg-gray-800/90 focus:outline-none focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-800 rounded-full text-sm p-2.5 transition-all duration-300 shadow-lg">
            <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z">
                </path>
            </svg>
        </button>
    </div>

    <!-- Main Content (Reduced Height) -->
    <div class="relative flex items-center justify-center min-h-screen px-4 py-8">
        <div class="text-center max-w-2xl w-full space-y-4 animate-slide-up">

            <!-- Logo (Smaller) -->
            <a href="{{ url('/') }}"
                class="inline-flex items-center justify-center mb-6 transition-all duration-300 hover:scale-105">
                @if(config('app.logo'))
                    <img src="{{ config('app.logo') }}" alt="{{ config('app.name') }} Logo" class="h-14 w-auto mr-2 drop-shadow-lg">
                    <span class="text-2xl font-black text-gray-900 dark:text-white">
                        {{ config('app.name', 'One LTG') }}
                    </span>
                @else
                    <span class="text-3xl font-black text-gray-900 dark:text-white">
                        {{ config('app.name', 'One LTG') }}
                    </span>
                @endif
            </a>

            <!-- Error Icon with Float Animation (Smaller) -->
            <div class="flex items-center justify-center mb-4 float-animation">
                <div class="relative">
                    <!-- Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-pink-500 rounded-full blur-xl opacity-30 animate-pulse"></div>
                    
                    <!-- Icon Container (Smaller) -->
                    <div class="relative bg-gradient-to-br from-red-500 to-pink-600 rounded-full p-5 shadow-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="white" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m0 3.75h.008v.008H12v-.008zm0-13.786A11.959 11.959 0 013.598 6
                                11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622
                                5.176-1.332 9-6.03 9-11.622
                                0-1.31-.21-2.57-.598-3.75A11.959 11.959 0 0112 2.214z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Error Code (Smaller) -->
            <h1 class="text-8xl md:text-9xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent mb-3 tracking-tight">
                @yield('code')
            </h1>

            <!-- Error Title (Bilingual) -->
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">
                <span class="lang-content" data-lang="en">@yield('title')</span>
                <span class="lang-content hidden" data-lang="id">
                    @switch(trim(View::yieldContent('code')))
                        @case('401')
                            Tidak Terotorisasi
                            @break
                        @case('403')
                            Akses Ditolak
                            @break
                        @case('404')
                            Halaman Tidak Ditemukan
                            @break
                        @case('419')
                            Halaman Kedaluwarsa
                            @break
                        @case('429')
                            Terlalu Banyak Permintaan
                            @break
                        @case('500')
                            Kesalahan Server
                            @break
                        @case('503')
                            Layanan Tidak Tersedia
                            @break
                        @default
                            Terjadi Kesalahan
                    @endswitch
                </span>
            </h2>

            <!-- Error Message (Bilingual, Smaller) -->
            <div class="text-lg text-gray-600 dark:text-gray-300 mb-6 max-w-lg mx-auto leading-relaxed">
                {{-- If in debug mode, show the actual exception message --}}
                @if(config('app.debug') && isset($exception) && $exception->getMessage())
                    <div class="glass-effect rounded-xl p-4 text-left text-xs font-mono overflow-x-auto shadow-xl">
                        <p class="font-bold mb-2 text-gray-900 dark:text-white text-sm">Error Details:</p>
                        <code class="text-red-600 dark:text-red-400 break-all">{{ $exception->getMessage() }}</code>
                    </div>
                @else
                    <p class="lang-content" data-lang="en">
                        @switch(trim(View::yieldContent('code')))
                            @case('401')
                                @yield('message', 'You are not authorized to access this page.')
                                @break
                            @case('403')
                                You do not have permission to access this page.
                                @break
                            @case('404')
                                The page you are looking for could not be found.
                                @break
                            @case('419')
                                Your session has expired. Please refresh and try again.
                                @break
                            @case('429')
                                Too many requests. Please slow down.
                                @break
                            @case('500')
                                A server error occurred. We are working to fix it.
                                @break
                            @case('503')
                                The service is currently unavailable. Please try again later.
                                @break
                            @default
                                An unexpected error occurred.
                        @endswitch
                    </p>
                    
                    <p class="lang-content hidden" data-lang="id">
                        @switch(trim(View::yieldContent('code')))
                            @case('401')
                                Anda tidak memiliki otorisasi untuk mengakses halaman ini.
                                @break
                            @case('403')
                                Anda tidak memiliki izin untuk mengakses halaman ini.
                                @break
                            @case('404')
                                Halaman yang Anda cari tidak dapat ditemukan.
                                @break
                            @case('419')
                                Sesi Anda telah berakhir. Silakan refresh dan coba lagi.
                                @break
                            @case('429')
                                Terlalu banyak permintaan. Harap perlambat.
                                @break
                            @case('500')
                                Terjadi kesalahan server. Kami sedang memperbaikinya.
                                @break
                            @case('503')
                                Layanan saat ini tidak tersedia. Silakan coba lagi nanti.
                                @break
                            @default
                                Terjadi kesalahan yang tidak terduga.
                        @endswitch
                    </p>
                @endif
            </div>

            <!-- Action Button (Smaller) -->
            <button onclick="window.history.back()"
                class="group inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-base font-semibold rounded-full shadow-xl hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800 transition-all duration-300 ease-in-out transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="lang-content" data-lang="en">Back To Previous Page</span>
                <span class="lang-content hidden" data-lang="id">Kembali ke Halaman Sebelumnya</span>
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Theme Toggle
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleBtn = document.getElementById('theme-toggle');

        if (localStorage.getItem('theme') === 'dark') {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function () {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Language Toggle
        const langToggleBtn = document.getElementById('lang-toggle');
        const langText = document.getElementById('lang-text');
        const langContents = document.querySelectorAll('.lang-content');
        
        // Get saved language or default to 'en'
        let currentLang = localStorage.getItem('lang') || 'en';
        
        // Set initial language
        updateLanguage(currentLang);
        
        langToggleBtn.addEventListener('click', function() {
            currentLang = currentLang === 'en' ? 'id' : 'en';
            updateLanguage(currentLang);
            localStorage.setItem('lang', currentLang);
        });
        
        function updateLanguage(lang) {
            langText.textContent = lang.toUpperCase();
            
            langContents.forEach(content => {
                if (content.dataset.lang === lang) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>