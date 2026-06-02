<x-layouts.app>
    {{-- Title otomatis muncul di tag <title> --}}
    @section('pageTitle', 'Berita')

    @php
        // Fungsi helper untuk mendapatkan thumbnail
        function getArticleThumbnail($article, $default = null) {
            // 1. Cek thumbnail field
            if (!empty($article->thumbnail)) {
                return asset('storage/' . $article->thumbnail);
            }

            // 2. Cari gambar pertama dari konten
            $content = $article->content;

            // Decode jika JSON string
            if (is_string($content)) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $content = $decoded;
                }
            }

            // Cari gambar pertama dalam array blocks
            if (is_array($content)) {
                foreach ($content as $block) {
                    if (isset($block['type']) && $block['type'] === 'image') {
                        $imageUrl = $block['data']['url_link'] ?? $block['data']['url'] ?? null;

                        if ($imageUrl) {
                            // Jika URL relatif, tambahkan storage path
                            if (!str_starts_with($imageUrl, 'http')) {
                                return asset('storage/' . $imageUrl);
                            }
                            return $imageUrl;
                        }
                    }
                }
            }

            // Fallback untuk HTML content
            if (is_string($content) && preg_match('/<img[^>]+src="([^">]+)"/i', $content, $matches)) {
                return $matches[1];
            }

            // 3. Fallback ke default atau logo
            return $default ?? asset('images/logo.png');
        }
    @endphp

    {{-- Konten Utama --}}
    <x-layouts.main>
        <div class="container mx-auto px-2 sm:px-4">

            {{-- Header Section dengan Gradient --}}
            <div class="mb-8 mt-2">
                <div class="text-center mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent mb-3">
                        Berita Terbaru
                    </h1>
                    <p class="text-gray-600 text-lg">Dapatkan informasi terkini dan terpercaya</p>
                </div>

                {{-- Search Bar dengan Design Modern --}}
                <form method="GET" class="max-w-2xl mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="w-full pl-12 pr-28 py-4 text-gray-700 bg-white border-2 border-gray-300 rounded-2xl focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all duration-300 shadow-sm hover:shadow-md"
                            placeholder="Cari berita yang kamu inginkan..."
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-2">
                            @if(request('search'))
                                <a href="{{ route('news.index') }}" class="flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 transition rounded-lg hover:bg-red-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                            <button
                                type="submit"
                                class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Category Filter - Sticky --}}
            <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-md rounded-2xl mb-8 border border-gray-100">
                <div class="px-4 py-4">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <h3 class="font-bold text-gray-800 text-lg">Kategori</h3>
                    </div>

                    <div class="flex flex-wrap gap-2 items-center">
                        {{-- All Categories Button --}}
                        <a href="{{ route('news.index') }}"
                           class="group px-4 py-2 rounded-xl font-medium text-sm transition-all duration-300 flex items-center gap-2
                                  {{ !request('category') ? 'bg-gradient-to-r from-red-600 to-pink-600 text-white shadow-lg shadow-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            <span>Semua</span>
                        </a>

                        {{-- Dynamic Categories from Database --}}
                        @foreach($categories as $category)
                            <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                               class="group px-4 py-2 rounded-xl font-medium text-sm transition-all duration-300 flex items-center gap-2
                                      {{ request('category') == $category->slug ? 'bg-gradient-to-r from-red-600 to-pink-600 text-white shadow-lg shadow-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:shadow-md' }}">
                                <span class="w-2 h-2 rounded-full {{ request('category') == $category->slug ? 'bg-white' : 'bg-red-600' }}"></span>
                                <span>{{ $category->name }}</span>
                                {{-- Optional: Show count --}}
                                @if(isset($category->news_count) && $category->news_count > 0)
                                    <span class="text-xs {{ request('category') == $category->slug ? 'text-white/80' : 'text-gray-500' }}">
                                        ({{ $category->news_count }})
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    {{-- Active Filter Indicator --}}
                    @if(request('category'))
                        @php
                            $activeCategory = $categories->firstWhere('slug', request('category'));
                        @endphp
                        @if($activeCategory)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span>Filter aktif:</span>
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-semibold">
                                        {{ $activeCategory->name }}
                                    </span>
                                    <a href="{{ route('news.index') }}" class="ml-auto text-red-600 hover:text-red-700 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Hapus Filter
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Konten Utama Full Width --}}
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

                {{-- Section Utama (Artikel & Entertainment) --}}
                <section class="w-full lg:w-3/5">
                    {{-- Daftar Berita --}}
                    @if($news->count())
                        {{-- Article Teratas / Featured Article --}}
                        @if($news->currentPage() == 1 && !request('search'))
                            @php
                                $featuredArticle = $news->first();
                                $featuredContent = $featuredArticle->content;

                                if (is_array($featuredContent)) {
                                    $featuredContent = collect($featuredContent)
                                        ->map(fn($block) => $block['data']['text'] ?? $block['data']['content'] ?? '')
                                        ->implode("\n");
                                }

                                $featuredContent = e(strip_tags($featuredContent));
                                $featuredImage = getArticleThumbnail($featuredArticle, $brandLogo = config('app.logo') ?? 'images/logo.png');
                            @endphp

                            <article class="mb-10 group bg-gradient-to-br from-red-50 via-white to-pink-50 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500">
                                <a href="{{ route('news.show', $featuredArticle->slug) }}" class="block">
                                    <div class="flex flex-col md:flex-row">
                                        {{-- Image Section --}}
                                        <div class="md:w-1/2 relative overflow-hidden h-64 md:h-auto">
                                    <img
                                        src="{{ $featuredImage ? (str_starts_with($featuredImage, 'http') ? $featuredImage : asset($featuredImage)) : asset(config('app.logo') ?? 'images/logo.png') }}"
                                        alt="{{ $featuredArticle->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                        onerror="this.onerror=null; this.src='{{ asset(config('app.logo') ?? 'images/logo.png') }}'"
                                    >
                                            {{-- Gradient Overlay --}}
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                                            {{-- Featured Badge --}}
                                            <div class="absolute top-6 left-6">
                                                <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-full shadow-lg">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    <span class="font-bold text-sm">Artikel Teratas</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Content Section --}}
                                        <div class="md:w-1/2 p-8 flex flex-col justify-center">
                                            {{-- Date --}}
                                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $featuredArticle->created_at->translatedFormat('d M Y') }}</span>
                                            </div>

                                            {{-- Title --}}
                                            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800 group-hover:text-red-600 transition-colors duration-300 leading-tight">
                                                {{ $featuredArticle->title }}
                                            </h2>

                                            {{-- Excerpt --}}
                                            <p class="text-gray-600 text-base leading-relaxed mb-6">
                                                {{ Str::limit($featuredContent, 200) }}
                                            </p>

                                            {{-- Read More Button --}}
                                            <div class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-semibold rounded-xl group-hover:shadow-lg transform group-hover:scale-105 transition-all duration-300 w-fit">
                                                <span>Baca Artikel Lengkap</span>
                                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endif
                        {{-- Hasil Pencarian Info --}}
                        @if(request('search'))
                            <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border-l-4 border-red-500">
                                <p class="text-gray-700">
                                    Ditemukan <span class="font-bold text-red-600">{{ $news->total() }}</span> berita untuk "<span class="font-semibold">{{ request('search') }}</span>"
                                </p>
                            </div>
                        @endif

                        {{-- Section Title untuk Berita Lainnya --}}
                        @if($news->currentPage() == 1 && !request('search'))
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                                    <span class="w-1 h-8 bg-gradient-to-b from-red-600 to-pink-600 rounded-full"></span>
                                    Berita Lainnya
                                </h3>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($news as $index => $item)
                                {{-- Skip artikel pertama jika halaman pertama dan bukan pencarian (karena sudah ditampilkan sebagai featured) --}}
                                @if($news->currentPage() == 1 && !request('search') && $index == 0)
                                    @continue
                                @endif

                                @php
                                    $content = $item->content;

                                    // Tangani builder array dari Filament
                                    if (is_array($content)) {
                                        $content = collect($content)
                                            ->map(fn($block) => $block['data']['text'] ?? $block['data']['content'] ?? '')
                                            ->implode("\n");
                                    }

                                    $content = e(strip_tags($content));
                                    $itemImage = getArticleThumbnail($item, $brandLogo = config('app.logo') ?? 'images/logo.png');
                                @endphp

                                <article class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative">
                                    <a href="{{ route('news.show', $item->slug) }}" class="block">
                                        {{-- Image Container --}}
                                        <div class="relative overflow-hidden h-56">
                                            <img
                                                src="{{ $itemImage }}"
                                                alt="{{ $item->title }}"
                                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                            >

                                            {{-- Gradient Overlay --}}
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                            {{-- Badge Kategori --}}
                                            <div class="absolute top-4 right-4">
                                                <span class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full shadow-lg">
                                                    Berita
                                                </span>
                                            </div>

                                            {{-- Featured Badge (Artikel Teratas) --}}
                                            @if($item->featured)
                                                <div class="absolute top-6 left-6 z-20">
                                                    <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-full shadow-lg">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        <span class="font-bold text-sm">Artikel Teratas</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Content --}}
                                        <div class="p-5">
                                            {{-- Date --}}
                                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $item->created_at->translatedFormat('d M Y') }}
                                            </div>

                                            {{-- Title --}}
                                            <h2 class="text-lg font-bold mb-3 text-gray-800 line-clamp-2 group-hover:text-red-600 transition-colors duration-300 leading-tight">
                                                {{ $item->title }}
                                            </h2>

                                            {{-- Excerpt --}}
                                            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                                {{ Str::limit($content, 150) }}
                                            </p>

                                            {{-- Read More --}}
                                            <div class="flex items-center text-red-600 font-semibold text-sm group-hover:gap-2 transition-all duration-300">
                                                <span>Baca Selengkapnya</span>
                                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination dengan Style Modern --}}
                        <div class="mt-12">
                            {{ $news->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-16 bg-white rounded-2xl shadow-md">
                            <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Berita</h3>
                            <p class="text-gray-500 mb-6">
                                @if(request('search'))
                                    Tidak ditemukan berita dengan kata kunci "{{ request('search') }}"
                                @else
                                    Belum ada berita yang dipublikasikan
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('news.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali ke Semua Berita
                                </a>
                            @endif
                        </div>
                    @endif
                </section>

                {{-- === SIDEBAR === --}}
                <aside class="w-full lg:w-2/5 bg-white rounded-none sm:rounded-xl shadow-sm">
                    <div class="bg-white rounded-2xl shadow-md lg:sticky lg:top-24">
                        <x-layouts.rightsidesection />
                    </div>
                </aside>

            </div>
        </div>
    </x-layouts.main>
</x-layouts.app>
