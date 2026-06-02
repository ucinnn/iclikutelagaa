<footer>
    <div class="bg-gray-800 text-gray-300 pt-10 pb-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4">

                {{-- Kolom Logo & Deskripsi --}}
                <div class="w-full lg:w-1/3 px-4 pb-5">
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

                    <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 min-w-0 mb-4">
                        <img
                            src="{{ $brandLogo }}"
                            alt="{{ $brandName }}"
                            style="height: {{ $brandLogoHeight }}; width: auto;"
                            class="object-contain"
                            onerror="this.src='{{ asset('images/logo.png') }}';"
                        >
                        <span class="text-2xl font-bold text-white">{{ $brandName }}</span>
                    </a>

                    @php
                        use App\Models\About;
                        $about = About::first();
                    @endphp

                    <p class="mb-6 text-sm text-gray-400 leading-relaxed">
                        {!! $about ? $about->description : 'Konten belum tersedia.' !!}
                    </p>

                    @php
                        $socialLinks = \App\Models\SocialLink::all();
                    @endphp

                    <ul class="flex items-center space-x-3 text-gray-400 border-t border-gray-700 pt-3">
                        @foreach($socialLinks as $link)
                            <li>
                                <a href="{{ $link->url }}" target="_blank" class="hover:text-blue-500 transition">
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
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Kolom Kategori Vertikal (3 Kolom Grid) --}}
                <div class="w-full lg:w-2/3 px-4 pb-5">
                    <div class="h-12 flex items-start mb-4">
                        <h5 class="text-lg font-semibold text-white">Kategori Berita</h5>
                    </div>
                        <div class="flex flex-wrap gap-2 items-center">

                    </div>

                    <ul class="grid grid-cols-2 md:grid-cols-4 gap-2">
                               @php
                                $categories = \App\Models\Category::withCount('news')
                                            ->orderBy('name')
                                            ->get();
                            @endphp
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                                class="block text-sm text-gray-300 hover:text-white transition-colors py-2">
                                    <span>{{ $category->name }}</span>
                                    {{-- Optional: Show count --}}
                                    @if(isset($category->news_count) && $category->news_count > 0)
                                        <span class="text-xs {{ request('category') == $category->slug ? 'text-white/80' : 'text-gray-500' }}">
                                            ({{ $category->news_count }})
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Navbar Horizontal di Bawah Sendiri --}}
        <div class="border-t border-gray-700 mt-8">
            <div class="container mx-auto px-4">
                <nav class="py-4">
                    <ul class="flex flex-wrap justify-center gap-6 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('news.index') }}" class="text-gray-300 hover:text-white transition">Berita</a></li>
                        <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-white transition">FAQ</a></li>
                        <li><a href="{{ route('helpdesk.index') }}" class="text-gray-300 hover:text-white transition">Tanya Jawab & Diskusi</a></li>
                        <li><a href="{{ route('surveidansaran') }}"  class="text-gray-300 hover:text-white transition">Survei & Saran</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="bg-gray-900">
        <div class="container mx-auto h-16 flex items-center justify-center py-4">
            <span class="text-sm text-gray-500 text-center">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script>
                {{ $brandName }}. All rights reserved.
                Made by
                <a href="https://github.com/FarhanFuady090" target="_blank" class="text-gray-300 hover:text-white">Farhan Fuady</a>
            </span>
        </div>
    </div>
</footer>
