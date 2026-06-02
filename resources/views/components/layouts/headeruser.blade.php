<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-red': '#c50000',
                        'gray-cl3': '#333',
                        'gray-cl6': '#666',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }

        /* Header glass effect */
        .glass-header {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.95);
        }

        /* Header scroll states */
        .header-default {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .header-scrolled {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        /* Logo transitions */
        .logo-default {
            height: 40px;
            transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo-scrolled {
            height: 32px;
        }

        .logo-text-default {
            font-size: 1.5rem;
            transition: font-size 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo-text-scrolled {
            font-size: 1.25rem;
        }

        /* Elegant hover effects */
        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link-scrolled {
            padding: 0.375rem 0.625rem;
            font-size: 0.8125rem;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #c50000, #ff3333);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: 80%;
        }

        /* Smooth button animations */
        .btn-primary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            box-shadow: 0 4px 15px rgba(135, 227, 196, 0.664);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            box-shadow: 0 4px 15px rgba(22, 101, 52, 0.2);
        }

         .btn-scrolled {
            padding: 0.5rem 1.25rem;
            font-size: 0.8125rem;
        }

        /* Ticker animation */
        @keyframes ticker {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .ticker-content {
            display: flex;
            animation: ticker 30s linear infinite;
            will-change: transform;
        }

        .ticker-item {
            flex-shrink: 0;
            padding: 0 3rem;
            white-space: nowrap;
        }

        .ticker-wrapper:hover .ticker-content {
            animation-play-state: paused;
        }

        /* Mobile menu animation */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-menu.open {
            max-height: 600px;
        }

        /* Search input elegant style */
        .search-input {
            transition: all 0.3s ease;
        }

        .search-input:focus {
            transform: scale(1.02);
        }

        /* Scrollbar hidden */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Logo hover effect */
        .logo-hover:hover img {
            transform: scale(1.05);
        }

        .logo-hover:hover span {
            color: #c50000;
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #333 0%, #666 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Breaking news badge animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .pulse-badge {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Smooth shadow on scroll */
        .header-shadow {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
        }

        /* Avatar gradient backgrounds */
        .avatar-gradient-1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .avatar-gradient-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .avatar-gradient-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

         /* Mobile header scroll */
        .mobile-header-default {
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }

        .mobile-header-scrolled {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .mobile-logo-default {
            height: 40px;
        }

        .mobile-logo-scrolled {
            height: 32px;
        }

        .mobile-logo-text-default {
            font-size: 1.25rem;
        }

        .mobile-logo-text-scrolled {
            font-size: 1.125rem;
        }

        /* Social links scroll animation */
        .social-bar-default {
            height: 2.5rem;
            opacity: 1;
        }

        .social-bar-scrolled {
            height: 2rem;
            opacity: 0.95;
        }

        .social-icon-default {
            width: 1.5rem;
            height: 1.5rem;
        }

        .social-icon-scrolled {
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Desktop Header -->
    <!-- Bagian Atas (Profil & Sosial) -->
    <div class="hidden lg:flex bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 sm:px-4 w-full flex justify-between items-center h-10">
            <!-- Social Links -->
            <div class="flex items-center space-x-4 text-slate-600 flex-shrink-0">

                @php
                    $socialLinks = \App\Models\SocialLink::all();
                @endphp

                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 hover:scale-110"
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
        </div>
    </div>
    <header class="glass-header h-16 sticky top-0 z-50 border-b border-gray-100 header-shadow hidden lg:block">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                    @php
                        try {
                            $brandName = filament()->getBrandName();
                        } catch (\Throwable $e) {
                            $brandName = null;
                        }

                        $brandName = $brandName ?? config('app.name') ?? env('APP_NAME', 'One LTG');

                        try {
                            $brandLogo = filament()->getBrandLogo();
                        } catch (\Throwable $e) {
                            $brandLogo = null;
                        }

                        if (empty($brandLogo)) {
                            $brandLogo = config('app.logo')
                                ? asset(config('app.logo'))
                                : (env('APP_LOGO')
                                    ? asset(env('APP_LOGO'))
                                    : asset('images/logo.png'));
                        }

                        try {
                            $brandLogoHeight = filament()->getBrandLogoHeight() ?? '40px';
                        } catch (\Throwable $e) {
                            $brandLogoHeight = '40px';
                        }
                    @endphp
                    <a href="{{ route('landingpage') }}" class="flex items-center gap-2 flex-shrink-0 min-w-0 mb-2 mt-2">
                        <img
                            src="{{ $brandLogo }}"
                            alt="{{ $brandName }}"
                            style="height: {{ $brandLogoHeight }}; width: auto;"
                            class="object-contain"
                            onerror="this.src='{{ asset('images/logo.png') }}';"
                        >
                        <span class="text-2xl font-bold text-black">{{ $brandName }}</span>
                    </a>


                <!-- Logout Button -->
                <a href="{{ route('loginpage') }}">
                    <button class="btn-primary px-6 py-2.5 text-sm font-semibold text-white rounded-lg">
                        MASUK
                    </button>
                </a>

            </div>
        </div>
    </header>

    <!-- Mobile Header -->
    <!-- Mobile Social Links Bar -->
    <div id="mobile-social-bar" class="lg:hidden bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200/60 shadow-sm sticky top-0 z-50 social-bar-default transition-all duration-300">
        <div class="px-4 flex justify-center items-center h-full overflow-x-auto no-scrollbar">
            <!-- Social Links -->
            <div class="flex items-center space-x-3 text-slate-600">
                @php
                    $socialLinks = \App\Models\SocialLink::all();
                @endphp

                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="social-icon-default flex items-center justify-center rounded-full hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 hover:scale-110 flex-shrink-0"
                    title="{{ ucfirst($link->platform) }}">
                    @switch(strtolower($link->platform))
                        {{-- Sosial Media Umum --}}
                        @case('facebook') <i class="fab fa-facebook-f text-xs"></i> @break
                        @case('twitter') <i class="fab fa-twitter text-xs"></i> @break
                        @case('x') <i class="fab fa-x-twitter text-xs"></i> @break
                        @case('instagram') <i class="fab fa-instagram text-xs"></i> @break
                        @case('tiktok') <i class="fab fa-tiktok text-xs"></i> @break
                        @case('youtube') <i class="fab fa-youtube text-xs"></i> @break
                        @case('threads') <i class="fab fa-threads text-xs"></i> @break
                        @case('snapchat') <i class="fab fa-snapchat-ghost text-xs"></i> @break
                        @case('pinterest') <i class="fab fa-pinterest-p text-xs"></i> @break
                        @case('reddit') <i class="fab fa-reddit-alien text-xs"></i> @break

                        {{-- Komunitas dan Chat --}}
                        @case('whatsapp') <i class="fab fa-whatsapp text-xs"></i> @break
                        @case('telegram') <i class="fab fa-telegram-plane text-xs"></i> @break
                        @case('discord') <i class="fab fa-discord text-xs"></i> @break
                        @case('line') <i class="fab fa-line text-xs"></i> @break
                        @case('messenger') <i class="fab fa-facebook-messenger text-xs"></i> @break

                        {{-- Profesional dan Portofolio --}}
                        @case('linkedin') <i class="fab fa-linkedin-in text-xs"></i> @break
                        @case('github') <i class="fab fa-github text-xs"></i> @break
                        @case('gitlab') <i class="fab fa-gitlab text-xs"></i> @break
                        @case('dribbble') <i class="fab fa-dribbble text-xs"></i> @break
                        @case('behance') <i class="fab fa-behance text-xs"></i> @break
                        @case('medium') <i class="fab fa-medium-m text-xs"></i> @break
                        @case('codepen') <i class="fab fa-codepen text-xs"></i> @break

                        {{-- Video & Musik --}}
                        @case('vimeo') <i class="fab fa-vimeo-v text-xs"></i> @break
                        @case('twitch') <i class="fab fa-twitch text-xs"></i> @break
                        @case('spotify') <i class="fab fa-spotify text-xs"></i> @break
                        @case('soundcloud') <i class="fab fa-soundcloud text-xs"></i> @break

                        {{-- E-Commerce & Platform --}}
                        @case('shopify') <i class="fab fa-shopify text-xs"></i> @break
                        @case('amazon') <i class="fab fa-amazon text-xs"></i> @break
                        @case('ebay') <i class="fab fa-ebay text-xs"></i> @break

                        {{-- Regional / Lain-lain --}}
                        @case('wechat') <i class="fab fa-weixin text-xs"></i> @break
                        @case('tumblr') <i class="fab fa-tumblr text-xs"></i> @break
                        @case('quora') <i class="fab fa-quora text-xs"></i> @break
                        @case('rss') <i class="fas fa-rss text-xs"></i> @break

                        {{-- Default --}}
                        @default <i class="fas fa-globe text-xs"></i>
                    @endswitch
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <header id="mobile-header" class="glass-header sticky z-40 lg:hidden border-b border-gray-100" style="top: 2.5rem;">
        <div class="px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('landingpage') }}" class="flex items-center gap-2 flex-shrink-0 min-w-0 mt-2 mb-2">
                    <img
                        id="mobile-logo"
                        src="{{ $brandLogo }}"
                        alt="{{ $brandName }}"
                        style="height: 40px; width: auto;"
                        class="object-contain mobile-logo-default"
                        onerror="this.src='{{ asset('images/logo.png') }}';"
                    >
                    <span id="mobile-logo-text" class="mobile-logo-text-default font-bold text-black">{{ $brandName }}</span>
                </a>

                <!-- Login Button -->
                <a href="{{ route('loginpage') }}">
                    <button class="btn-primary px-6 py-2.5 text-sm font-semibold text-white rounded-lg">
                        LOGIN
                    </button>
                </a>
            </div>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                const icon = menuToggle.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.remove('open');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            });
        }

        // Header shrink on scroll
        let lastScroll = 0;
        const desktopHeader = document.getElementById('desktop-header');
        const mobileHeader = document.getElementById('mobile-header');
        const mobileSocialBar = document.getElementById('mobile-social-bar');
        const desktopLogo = document.getElementById('desktop-logo');
        const desktopLogoText = document.getElementById('desktop-logo-text');
        const mobileLogo = document.getElementById('mobile-logo');
        const mobileLogoText = document.getElementById('mobile-logo-text');
        const navLinks = document.querySelectorAll('.nav-link');
        const logoutBtn = document.getElementById('logout-btn');
        const socialIcons = document.querySelectorAll('.social-icon-default');

        window.addEventListener('scroll', () => {
            const currentScroll = window.scrollY;

            if (currentScroll > 50) {
                // Mobile social bar shrink
                if (mobileSocialBar) {
                    mobileSocialBar.classList.remove('social-bar-default');
                    mobileSocialBar.classList.add('social-bar-scrolled');
                    mobileSocialBar.style.top = '0';

                    socialIcons.forEach(icon => {
                        icon.classList.remove('social-icon-default');
                        icon.classList.add('social-icon-scrolled');
                    });
                }

                // Mobile header position adjustment
                if (mobileHeader) {
                    mobileHeader.style.top = '2rem';
                    mobileHeader.classList.remove('mobile-header-default');
                    mobileHeader.classList.add('mobile-header-scrolled');
                }

                if (mobileLogo) {
                    mobileLogo.classList.remove('mobile-logo-default');
                    mobileLogo.classList.add('mobile-logo-scrolled');
                }

                if (mobileLogoText) {
                    mobileLogoText.classList.remove('mobile-logo-text-default');
                    mobileLogoText.classList.add('mobile-logo-text-scrolled');
                }
            } else {
                // Mobile social bar expand
                if (mobileSocialBar) {
                    mobileSocialBar.classList.add('social-bar-default');
                    mobileSocialBar.classList.remove('social-bar-scrolled');

                    socialIcons.forEach(icon => {
                        icon.classList.add('social-icon-default');
                        icon.classList.remove('social-icon-scrolled');
                    });
                }

                // Mobile header position reset
                if (mobileHeader) {
                    mobileHeader.style.top = '2.5rem';
                    mobileHeader.classList.add('mobile-header-default');
                    mobileHeader.classList.remove('mobile-header-scrolled');
                }

                if (mobileLogo) {
                    mobileLogo.classList.add('mobile-logo-default');
                    mobileLogo.classList.remove('mobile-logo-scrolled');
                }

                if (mobileLogoText) {
                    mobileLogoText.classList.add('mobile-logo-text-default');
                    mobileLogoText.classList.remove('mobile-logo-text-scrolled');
                }
            }

            lastScroll = currentScroll;
        }, { passive: true });
    </script>
</body>
</html>
