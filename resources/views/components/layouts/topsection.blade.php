<section class="bg-gradient-to-br from-gray-50 via-white to-gray-50 pt-8 pb-12">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1500px]">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-1 w-12 bg-gradient-to-r from-red-600 to-red-400 rounded-full"></div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Berita Unggulan</h2>
            </div>
            <p class="text-gray-600 ml-15">Lihat berita dan informasi terbaru</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @php
                use Illuminate\Support\Facades\Config;

                // Ambil hanya berita yang featured = true dan status = published
                $featuredNews = App\Models\News::where('featured', true)
                    ->where('status', 'published')
                    ->latest()
                    ->take(5)
                    ->get();

                $featured = $featuredNews->first();
                $others = $featuredNews->skip(1)->take(4);

                // Ambil logo default dari config atau asset
                $defaultLogo = Config::get('app.logo')
                    ? asset(Config::get('app.logo'))
                    : asset('images/logo.png');

                // Fungsi untuk mendapatkan thumbnail
                function getNewsThumbnail($news, $defaultLogo) {
                    // 1. Cek thumbnail field
                    if (!empty($news->thumbnail)) {
                        return asset('storage/' . $news->thumbnail);
                    }

                    // 2. Cari gambar pertama dari konten
                    $content = $news->content;

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

                    // 3. Fallback ke logo
                    return $defaultLogo;
                }
            @endphp
            
            {{-- ✨ Berita Utama - Modern Card --}}
            @if ($featured)
                @php
                    $featuredThumb = getNewsThumbnail($featured, $defaultLogo);
                    
                    // Pastikan URL gambar valid
                    if ($featuredThumb && !str_starts_with($featuredThumb, 'http') && !str_starts_with($featuredThumb, '/')) {
                        $featuredThumb = asset($featuredThumb);
                    }
                    
                    // Fallback jika kosong
                    if (empty($featuredThumb)) {
                        $featuredThumb = $defaultLogo ?? asset('images/default-thumbnail.jpg');
                    }
                @endphp
                <div class="lg:col-span-8">
                    <div class="group relative rounded-2xl overflow-hidden h-[200px] sm:h-[300px] md:h-[450px] lg:h-[550px] shadow-xl hover:shadow-2xl transition-all duration-500">
                        
                        {{-- Gunakan <img> tag sebagai fallback jika background-image gagal --}}
                        <img 
                            src="{{ $featuredThumb }}" 
                            alt="{{ $featured->title }}"
                            class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                            onerror="this.src='{{ $defaultLogo ?? asset('images/default-thumbnail.jpg') }}'"
                        />
            
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-90"></div>
                        <!-- Hover Effect -->
                        <a href="{{ route('news.show', $featured->slug) }}"
                           class="absolute inset-0 z-10"></a>
                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 z-20 p-6 md:p-8 lg:p-10">
                            @if($featured->category->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($featured->category as $cat)
                                        <a href="{{ route('news.category.show', $cat->slug) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors duration-300 shadow-lg">
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <h3 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white leading-tight mb-4 line-clamp-3 group-hover:text-red-400 transition-colors duration-300">
                                {{ $featured->title }}
                            </h3>
                            <div class="flex items-center gap-3 text-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($featured->author ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium">{{ $featured->author ?? 'Unknown' }}</span>
                                </div>
                                <span class="text-gray-400">•</span>
                                <span class="text-sm">{{ $featured->published_at ? $featured->published_at->format('d M Y') : '—' }}</span>
                            </div>
                        </div>
                        <!-- Corner Accent -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-red-600/20 to-transparent"></div>
                    </div>
                </div>
            @endif
            
                       {{-- ✨ Berita Lainnya - Grid Modern --}}
            <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                @forelse ($others as $item)
                    @php
                        $itemThumb = getNewsThumbnail($item, $defaultLogo);
                        if ($itemThumb && !str_starts_with($itemThumb, 'http') && !str_starts_with($itemThumb, '/')) {
                            $itemThumb = asset($itemThumb);
                        }
                        if (empty($itemThumb)) {
                            $itemThumb = $defaultLogo ?? asset('images/default-thumbnail.jpg');
                        }
                    @endphp
                    <div class="group relative rounded-xl overflow-hidden h-[130px] shadow-lg hover:shadow-xl transition-all duration-500 bg-white">
                        <!-- Image -->
                        <img
                            src="{{ $itemThumb }}"
                            alt="{{ $item->title }}"
                            class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                            onerror="this.src='{{ $defaultLogo ?? asset('images/default-thumbnail.jpg') }}'"
                        />
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-transparent"></div>
                        <!-- Content -->
                        <div class="absolute inset-0 z-20 p-4 flex flex-col justify-between pointer-events-none">
                            <div>
                                @if($item->category->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 mb-2">
                                        @foreach($item->category as $cat)
                                            <a href="{{ route('news.category.show', $cat->slug) }}"
                                               class="pointer-events-auto inline-block text-[10px] font-bold uppercase tracking-wide bg-red-600 text-white px-2 py-0.5 rounded hover:bg-red-700 transition-colors relative z-30">
                                                {{ $cat->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <h3 class="text-base md:text-lg font-bold text-white leading-snug line-clamp-2 group-hover:text-red-400 transition-colors duration-300">
                                    {{ $item->title }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 text-gray-300 text-xs">
                                <span class="font-medium">{{ $item->author ?? 'Unknown' }}</span>
                                <span class="text-gray-500">•</span>
                                <span>{{ $item->published_at ? $item->published_at->format('d M Y') : '—' }}</span>
                            </div>
                        </div>
                        <!-- Link utama di atas segalanya -->
                        <a href="{{ route('news.show', $item->slug) }}"
                           class="absolute inset-0 z-30"
                           aria-label="{{ $item->title }}"></a>
                        <!-- Accent Line -->
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-red-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left z-40"></div>
                    </div>
                @empty
                    <div class="col-span-full flex items-center justify-center h-full py-12">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada berita unggulan tambahan</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
