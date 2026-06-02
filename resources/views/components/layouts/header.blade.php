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
            background: linear-gradient(135deg, #c50000 0%, #ff3333 100%);
            box-shadow: 0 4px 15px rgba(197, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(197, 0, 0, 0.3);
        }

         .btn-scrolled {
            padding: 0.5rem 1.25rem;
            font-size: 0.8125rem;
        }

        /* Ticker animation */
        @keyframes ticker {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .ticker-content {
            display: flex;
            animation: ticker 40s linear infinite;
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Desktop Header -->
    <header class="glass-header h-16 sticky top-0 z-50 border-b border-gray-100 header-shadow hidden lg:block">
        <div class="w-full bg-white shadow-sm">
            <div class="max-w-8xl mx-auto px-6 lg:px-6 flex items-center justify-between gap-4">
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
                    <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 min-w-0 mb-2 mt-2">
                        <img
                            src="{{ $brandLogo }}"
                            alt="{{ $brandName }}"
                            style="height: {{ $brandLogoHeight }}; width: auto;"
                            class="object-contain"
                            onerror="this.src='{{ asset('images/logo.png') }}';"
                        >
                        <span class="text-2xl font-bold text-black">{{ $brandName }}</span>
                    </a>


                <!-- Navigation -->
                <nav class="flex-1">
                    <ul class="flex items-center justify-center gap-1">
                        <li>
                            <a href="{{ route('home') }}"
                             class="nav-link py-2 px-3 uppercase whitespace-nowrap transition-colors duration-200 font-semibold {{ request()->routeIs('home') ? 'text-custom-red' : 'text-gray-700 hover:text-custom-red' }}">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('news.index') }}"
                             class="nav-link py-2 px-3 uppercase whitespace-nowrap transition-colors duration-200 {{ request()->routeIs('news.*') ? 'text-custom-red font-semibold' : 'text-gray-700 hover:text-custom-red' }}">
                             Berita
                            </a>
                        </li>
                        <li>
                             <a href="{{ route('faq') }}"
                             class="nav-link py-2 px-3 uppercase whitespace-nowrap transition-colors duration-200 {{ request()->routeIs('faq') ? 'text-custom-red font-semibold' : 'text-gray-700 hover:text-custom-red' }}">
                              FAQ
                            </a>
                        </li>
                        <li>
                         <a href="{{ route('helpdesk.index') }}"
                             class="nav-link py-2 px-3 uppercase whitespace-nowrap transition-colors duration-200 {{ request()->routeIs('helpdesk.*') ? 'text-custom-red font-semibold' : 'text-gray-700 hover:text-custom-red' }}">
                             Tanya Jawab & Diskusi
                            </a>
                        </li>
                        <li>
                             <a href="{{ route('surveidansaran') }}"
                             class="nav-link py-2 px-3 uppercase whitespace-nowrap transition-colors duration-200 {{ request()->routeIs('surveidansaran') ? 'text-custom-red font-semibold' : 'text-gray-700 hover:text-custom-red' }}">
                             Survei
                            </a>
                        </li>
                        <li>
                             <a href="{{ route('whistle-blowing.index') }}"
                             class="nav-link py-2 px-3 uppercase whitespace-nowrap transition-colors duration-200 {{ request()->routeIs('whistle-blowing.*') ? 'text-custom-red font-semibold' : 'text-gray-700 hover:text-custom-red' }}">
                             Whistle Blowing
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                     @csrf
                    <button class="btn-primary px-6 py-2.5 text-sm font-semibold text-white rounded-lg">
                        KELUAR
                    </button>
                </form>

            </div>
        </div>

    </header>

    <!-- Mobile Header -->
    <header class="glass-header h-16 sticky top-0 z-50 lg:hidden border-b border-gray-100">
        <div class="w-full mx-auto px-6 lg:px-6 py-4 sm:py-4 lg:py-8">
            <div class="flex items-center justify-between">

                <!-- Menu Toggle -->
                <button id="mobile-menu-toggle" class="p-2 text-gray-700 hover:bg-red-50 rounded-lg">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 min-w-0 mt-1 mb-1">
                    <img
                        src="{{ $brandLogo }}"
                        alt="{{ $brandName }}"
                        style="height: 36px; width: auto;"
                        class="object-contain sm:h-10"
                        onerror="this.src='{{ asset('images/logo.png') }}';"
                    >
                    <span class="text-lg sm:text-xl font-bold text-black">{{ $brandName }}</span>
                </a>

                 <livewire:announcement-list/>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu bg-white border-gray-100">
            <div class="px-4 py-4">
                <!-- User Profile -->
                   @auth
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 avatar-gradient-1 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}"
                                    alt="User Avatar"
                                    class="rounded-full border border-gray-300 object-cover flex-shrink-0">
                            @else
                                {{ $initials }}
                            @endif
                            </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ Auth::user()->NIK }}</p>
                        </div>
                        <a href="{{ route('edit-profile') }}"
                        class="ml-2 px-3 py-1.5 text-xs font-medium text-white rounded-lg hover:bg-blue-600 transition whitespace-nowrap {{ request()->routeIs('edit-profile') ? 'bg-blue-900' : 'bg-blue-500' }}">
                            Edit Profil
                        </a>
                    </div>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                    @endauth

                <!-- Navigation Links -->
                <nav class="space-y-1 mb-6">
                    <a href="{{ route('home') }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg
                        {{ request()->routeIs('home') ? 'text-custom-red bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-home w-5"></i> Beranda
                    </a>

                    <a href="{{ route('news.index') }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg
                        {{ request()->routeIs('news.*') ? 'text-custom-red bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-newspaper w-5"></i> Berita
                    </a>

                    <a href="{{ route('faq') }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg
                        {{ request()->routeIs('faq') ? 'text-custom-red bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-question-circle w-5"></i> FAQ
                    </a>

                    <a href="{{ route('helpdesk.index') }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg
                        {{ request()->routeIs('helpdesk.*') ? 'text-custom-red bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-comments w-5"></i> Tanya Jawab & Diskusi
                    </a>

                    <a href="{{ route('surveidansaran') }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg
                        {{ request()->routeIs('surveidansaran') ? 'text-custom-red bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-poll w-5"></i> Survei
                    </a>
                    
                    <a href="{{ route('whistle-blowing.index') }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg
                        {{ request()->routeIs('whistle-blowing') ? 'text-custom-red bg-red-50' : 'text-gray-700 hover:bg-gray-50' }}">
<i class="fas fa-bullhorn w-5"></i> Whistle Blowing
                    </a>
                </nav>

                <!-- Social Links -->
                <div class="flex justify-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    @foreach($socialLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                            class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full" title="{{ ucfirst($link->platform) }}">
                            @switch(strtolower($link->platform))
                            {{-- Sosial Media Umum --}}
                            @case('facebook') <i class="fab fa-facebook-f"></i> @break
                            @case('twitter') <i class="fab fa-twitter"></i> @break
                            @case('x') <i class="fab fa-x-twitter"></i> @break
                            @case('instagram') <i class="fab fa-instagram"></i> @break
                            @case('tiktok') <i class="fab fa-tiktok"></i> @break
                            @case('youtube') <i class="fab fa-youtube"></i> @break
                            @case('threads') <i class="fab fa-threads"></i> @break
                            @case('snapchat') <i class="fab fa-snapchat-ghost"></i> @break
                            @case('pinterest') <i class="fab fa-pinterest-p"></i> @break
                            @case('reddit') <i class="fab fa-reddit-alien"></i> @break

                            {{-- Komunitas dan Chat --}}
                            @case('whatsapp') <i class="fab fa-whatsapp"></i> @break
                            @case('telegram') <i class="fab fa-telegram-plane"></i> @break
                            @case('discord') <i class="fab fa-discord"></i> @break
                            @case('line') <i class="fab fa-line"></i> @break
                            @case('messenger') <i class="fab fa-facebook-messenger"></i> @break

                            {{-- Profesional dan Portofolio --}}
                            @case('linkedin') <i class="fab fa-linkedin-in"></i> @break
                            @case('github') <i class="fab fa-github"></i> @break
                            @case('gitlab') <i class="fab fa-gitlab"></i> @break
                            @case('dribbble') <i class="fab fa-dribbble"></i> @break
                            @case('behance') <i class="fab fa-behance"></i> @break
                            @case('medium') <i class="fab fa-medium-m"></i> @break
                            @case('codepen') <i class="fab fa-codepen"></i> @break

                            {{-- Video & Musik --}}
                            @case('vimeo') <i class="fab fa-vimeo-v"></i> @break
                            @case('twitch') <i class="fab fa-twitch"></i> @break
                            @case('spotify') <i class="fab fa-spotify"></i> @break
                            @case('soundcloud') <i class="fab fa-soundcloud"></i> @break

                            {{-- E-Commerce & Platform --}}
                            @case('shopify') <i class="fab fa-shopify"></i> @break
                            @case('amazon') <i class="fab fa-amazon"></i> @break
                            @case('ebay') <i class="fab fa-ebay"></i> @break

                            {{-- Regional / Lain-lain --}}
                            @case('wechat') <i class="fab fa-weixin"></i> @break
                            @case('tumblr') <i class="fab fa-tumblr"></i> @break
                            @case('quora') <i class="fab fa-quora"></i> @break
                            @case('rss') <i class="fas fa-rss"></i> @break

                            {{-- Default --}}
                            @default <i class="fas fa-globe"></i>
                            @endswitch
                        </a>
                    @endforeach
                </div>

                <!-- Logout Button -->
                @auth
                <div class="px-4 pb-4 border-t border-gray-100 flex justify-center">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-2 px-6 btn-primary text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i> KELUAR
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Breaking News Bar -->
  <div class="bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 shadow-sm">
    <div class="w-full mx-auto px-4 sm:px-6 py-1.5 sm:py-2.5 lg:py-3">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-2 sm:gap-4">

            <!-- Label & Ticker -->
            <div class="flex items-center gap-2 sm:gap-3 flex-1.25 min-w-0 w-full">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-0.5 sm:py-1 bg-gradient-to-r from-red-600 to-red-500 text-white font-bold text-[10px] sm:text-xs rounded-full shadow-lg pulse-badge">
                        <i class="fas fa-bolt text-yellow-300 text-[11px] sm:text-sm"></i>
                        BREAKING NEWS
                    </span>
                </div>

                <!-- News Ticker -->
                <div class="relative overflow-hidden flex-1 min-w-0 bg-white rounded-full px-3 sm:px-4 py-1 sm:py-2 shadow-sm">
                    <div class="ticker-wrapper">
                        <div class="ticker-content">
                            @php
                                use Illuminate\Support\Facades\Config;

                                $latestNews = App\Models\News::where('status', 'published')
                                    ->latest()
                                    ->take(10)
                                    ->get();
                            @endphp

                            @if ($latestNews->count() > 0)
                                @foreach ($latestNews as $item)
                                    <a href="{{ route('news.show', $item->slug) }}"
                                       class="ticker-item text-gray-700 text-xs sm:text-sm font-medium hover:text-red-600 transition-colors duration-300">
                                        {{ $item->title }}
                                    </a>
                                @endforeach
                                {{-- Duplikasi untuk loop mulus --}}
                                @foreach ($latestNews as $item)
                                    <a href="{{ route('news.show', $item->slug) }}"
                                       class="ticker-item text-gray-700 text-xs sm:text-sm font-medium hover:text-red-600 transition-colors duration-300">
                                        {{ $item->title }}
                                    </a>
                                @endforeach
                            @else
                                <span class="text-gray-500 text-xs sm:text-sm truncate block py-1">
                                    Tidak ada berita terbaru
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Desktop -->
            <div class="hidden md:block flex-shrink-0">
                <form action="{{ route('news.search') }}" method="GET" class="relative">
                    <input
                        type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul atau kategori berita..."
                        class="search-input w-72 pl-4 pr-12 py-1.5 sm:py-2 text-sm border border-gray-200 rounded-full focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-50 bg-white shadow-sm"
                    >
                    <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Search Mobile -->
        <div class="block md:hidden mt-1.5 sm:mt-2">
            <form action="{{ route('news.search') }}" method="GET" class="relative">
                <input
                    type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul atau kategori berita..."
                    class="w-full pl-4 pr-10 py-1.5 text-xs sm:text-sm border border-gray-200 rounded-full focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-50 bg-white shadow-sm"
                >
                <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full">
                    <i class="fas fa-search text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>


    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

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

        // Add scroll shadow effect
       // Header shrink on scroll
        let lastScroll = 0;
        const desktopHeader = document.getElementById('desktop-header');
        const mobileHeader = document.getElementById('mobile-header');
        const desktopLogo = document.getElementById('desktop-logo');
        const desktopLogoText = document.getElementById('desktop-logo-text');
        const mobileLogo = document.getElementById('mobile-logo');
        const mobileLogoText = document.getElementById('mobile-logo-text');
        const navLinks = document.querySelectorAll('.nav-link');
        const logoutBtn = document.getElementById('logout-btn');

        window.addEventListener('scroll', () => {
            const currentScroll = window.scrollY;

            if (currentScroll > 50) {
                // Desktop header shrink
                desktopHeader.classList.remove('header-default');
                desktopHeader.classList.add('header-scrolled');
                desktopLogo.classList.remove('logo-default');
                desktopLogo.classList.add('logo-scrolled');
                desktopLogoText.classList.remove('logo-text-default');
                desktopLogoText.classList.add('logo-text-scrolled');
                logoutBtn.classList.add('btn-scrolled');

                navLinks.forEach(link => {
                    link.classList.add('nav-link-scrolled');
                });

                // Mobile header shrink
                mobileHeader.classList.remove('mobile-header-default');
                mobileHeader.classList.add('mobile-header-scrolled');
                mobileLogo.classList.remove('mobile-logo-default');
                mobileLogo.classList.add('mobile-logo-scrolled');
                mobileLogoText.classList.remove('mobile-logo-text-default');
                mobileLogoText.classList.add('mobile-logo-text-scrolled');

                // Enhanced shadow
                desktopHeader.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.1)';
                mobileHeader.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.1)';
            } else {
                // Desktop header expand
                desktopHeader.classList.add('header-default');
                desktopHeader.classList.remove('header-scrolled');
                desktopLogo.classList.add('logo-default');
                desktopLogo.classList.remove('logo-scrolled');
                desktopLogoText.classList.add('logo-text-default');
                desktopLogoText.classList.remove('logo-text-scrolled');
                logoutBtn.classList.remove('btn-scrolled');

                navLinks.forEach(link => {
                    link.classList.remove('nav-link-scrolled');
                });

                // Mobile header expand
                mobileHeader.classList.add('mobile-header-default');
                mobileHeader.classList.remove('mobile-header-scrolled');
                mobileLogo.classList.add('mobile-logo-default');
                mobileLogo.classList.remove('mobile-logo-scrolled');
                mobileLogoText.classList.add('mobile-logo-text-default');
                mobileLogoText.classList.remove('mobile-logo-text-scrolled');

                // Normal shadow
                desktopHeader.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.05)';
                mobileHeader.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.05)';
            }

            lastScroll = currentScroll;
        }, { passive: true });
    </script>
</body>
</html>
