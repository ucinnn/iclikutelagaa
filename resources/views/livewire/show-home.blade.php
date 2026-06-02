<x-layouts.app>

    @section('pageTitle', 'Home')

    {{-- Header Sticky --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm">

    </header>

    {{-- Top Section --}}
    <x-layouts.topsection :news="$news" />

    {{-- POP-UP NEWS - Modern Design --}}
    @if($popups->count() > 0)
    <div id="popup-container"></div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popups = @json($popups);
            const USER_ID = '{{ auth()->id() }}';
            const STORAGE_KEY = 'popup_dismissed_' + USER_ID;
            let currentIndex = 0;
    
            // Jika user sudah memilih tidak tampilkan lagi, skip
            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                return;
            }
    
            function getImageUrl(imagePath) {
                if (!imagePath) return null;
                let filename = imagePath.split('/').pop();
                return '/popup-image/' + filename;
            }
    
            function showPopup(index) {
                if (index >= popups.length) {
                    document.getElementById('popup-container').innerHTML = '';
                    return;
                }
    
                const popup = popups[index];
                const container = document.getElementById('popup-container');
                const imageUrl = getImageUrl(popup.image);
    
                const image = imageUrl ? `
                    <div class="relative w-full h-80 overflow-hidden rounded-2xl mb-6">
                        <img src="${imageUrl}"
                            alt="${popup.title}"
                            class="w-full h-full object-cover"
                            onerror="this.parentElement.style.display='none'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>` : '';
    
                const html = `
                    <div id="popup-overlay" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
                        <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden relative animate-slide-up">
                            <button onclick="closePopup()"
                                class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center bg-white/90 hover:bg-white rounded-full shadow-lg text-gray-600 hover:text-gray-900 transition-all duration-300 hover:rotate-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
    
                            <div class="overflow-y-auto max-h-[90vh] p-8">
                                ${image}
                                <div class="mb-6">
                                    <div class="inline-block px-4 py-1.5 bg-gradient-to-r from-red-600 to-red-500 text-white text-xs font-bold uppercase tracking-wider rounded-full mb-4">
                                        Berita Penting
                                    </div>
                                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">${popup.title}</h2>
                                </div>
    
                                <div class="text-gray-700 text-base leading-relaxed prose prose-lg max-w-none">
                                    ${decodeHTML(popup.content ?? '')}
                                </div>
    
                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    ${popups.length > 1 ? `
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm text-gray-500 font-medium">${index + 1} of ${popups.length}</span>
                                        <button onclick="nextPopup()"
                                            class="group relative px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                                            <span class="relative z-10 flex items-center gap-2">
                                                Berita Selanjutnya
                                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </span>
                                            <div class="absolute inset-0 bg-gradient-to-r from-red-700 to-red-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                                        </button>
                                    </div>` : ''}
    
                                    {{-- Checkbox jangan tampilkan lagi --}}
                                    <div class="flex items-center justify-center">
                                        <label class="flex items-center gap-2 cursor-pointer select-none group">
                                            <input type="checkbox" id="dont-show-again"
                                                class="w-4 h-4 rounded border-gray-300 cursor-pointer accent-red-500">
                                            <span class="text-sm text-gray-400 group-hover:text-gray-600 transition-colors">
                                                Jangan tampilkan lagi selama sesi login ini
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
    
                container.innerHTML = html;
            }
    
            window.closePopup = function () {
                const checkbox = document.getElementById('dont-show-again');
                if (checkbox && checkbox.checked) {
                    localStorage.setItem(STORAGE_KEY, 'true');
                }
    
                const overlay = document.getElementById('popup-overlay');
                overlay?.classList.add('animate-fade-out');
                setTimeout(() => {
                    document.getElementById('popup-container').innerHTML = '';
                }, 300);
            }
    
            window.nextPopup = function () {
                const checkbox = document.getElementById('dont-show-again');
                if (checkbox && checkbox.checked) {
                    localStorage.setItem(STORAGE_KEY, 'true');
                    closePopup();
                    return;
                }
                currentIndex++;
                showPopup(currentIndex);
            }
    
            function decodeHTML(html) {
                const txt = document.createElement('textarea');
                txt.innerHTML = html;
                return txt.value;
            }
    
            showPopup(currentIndex);
        });
    </script>
    @endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutForm = document.querySelector('form[action="{{ route('logout') }}"]');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function () {
                const USER_ID = '{{ auth()->id() }}';
                localStorage.removeItem('popup_dismissed_' + USER_ID);
            });
        }
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fade-out {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
    .animate-fade-out { animation: fade-out 0.3s ease-out; }
    .animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
</style>

    {{-- Konten Utama --}}
    <x-layouts.main>

        <div class="container mx-auto px-4 py-8 lg:py-12">
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

                {{-- Section Utama --}}
                <section class="w-full lg:w-3/5">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        {{-- Berita Section --}}
                        <div class="p-2 md:p-8">
                            <div class="flex flex-wrap justify-between items-center mb-6 md:mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-1.5 bg-gradient-to-b from-red-600 to-red-400 rounded-full"></div>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Berita Terbaru</h3>
                                </div>
                                <a href="{{ route('news.index') }}"
                                class="group flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">
                                    <span>Lihat Semua</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                               {{-- Artikel Utama --}}
                                @if($mainArticle)
                                    <div class="md:col-span-1">
                                        <div class="group">
                                            @php
                                                $defaultLogo = Config::get('app.logo') ? asset(Config::get('app.logo')) : asset('images/logo.png');
                                                
                                                $Thumb = null;
                                                if (!empty($mainArticle->thumbnail)) {
                                                    if (str_starts_with($mainArticle->thumbnail, 'http')) {
                                                        $Thumb = $mainArticle->thumbnail;
                                                    } elseif (str_starts_with($mainArticle->thumbnail, 'storage/')) {
                                                        $Thumb = asset($mainArticle->thumbnail);
                                                    } else {
                                                        $Thumb = asset('storage/' . $mainArticle->thumbnail);
                                                    }
                                                }
                                                $Thumb = $Thumb ?? $defaultLogo;
                                            @endphp
                                
                                            {{-- Thumbnail --}}
                                            <a href="{{ route('news.show', $mainArticle->slug) }}"
                                               class="block relative overflow-hidden rounded-2xl mb-5 shadow-md hover:shadow-xl transition-shadow duration-300">
                                                <div class="aspect-[4/3] overflow-hidden">
                                                    <img 
                                                        src="{{ $Thumb }}" 
                                                        alt="{{ $mainArticle->title }}"
                                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                                        onerror="this.onerror=null; this.src='{{ $defaultLogo }}';"
                                                    >
                                                </div>
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </a>
                                
                                            <div class="space-y-4">
                                
                                                {{-- Category --}}
                                                @if($mainArticle->category->isNotEmpty())
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($mainArticle->category as $cat)
                                                            <a href="{{ route('news.category.show', $cat->slug) }}"
                                                               class="inline-flex items-center px-3 py-1 text-xs font-bold uppercase tracking-wider text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-300">
                                                                {{ $cat->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                
                                                {{-- Judul --}}
                                                <h4>
                                                    <a href="{{ route('news.show', $mainArticle->slug) }}"
                                                       class="text-xl md:text-2xl font-bold text-gray-900 hover:text-red-600 transition-colors line-clamp-2 leading-tight">
                                                        {{ $mainArticle->title }}
                                                    </a>
                                                </h4>
                                
                                                {{-- Author & Tanggal --}}
                                                <div class="flex items-center gap-3 text-sm text-gray-500">
                                                    @if(!empty($mainArticle->author))
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                                                {{ substr($mainArticle->author, 0, 1) }}
                                                            </div>
                                                            <div class="flex flex-col leading-tight">
                                                                <span class="font-semibold text-gray-800 text-sm">{{ $mainArticle->author }}</span>
                                                                <span class="text-xs text-gray-400">Penulis</span>
                                                            </div>
                                                        </div>
                                                        <span class="text-gray-300">|</span>
                                                    @endif
                                
                                                    <div class="flex flex-col leading-tight">
                                                        <span class="text-xs text-gray-400">Diterbitkan</span>
                                                        <span class="font-medium text-gray-600 text-sm">
                                                            {{ $mainArticle->published_at?->format('d M Y') ?? '—' }}
                                                        </span>
                                                    </div>
                                                </div>
                                
                                                {{-- Tags --}}
                                                @if(!empty($mainArticle->tags) && $mainArticle->tags->isNotEmpty())
                                                    <div class="flex flex-wrap gap-2 pt-1">
                                                        @foreach($mainArticle->tags as $tag)
                                                            <a href="{{ route('news.tag', $tag->slug) }}"
                                                               class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 hover:text-gray-900 transition-colors duration-200">
                                                                <span class="text-gray-400">#</span>{{ $tag->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Artikel Kecil --}}
                                <div class="md:col-span-1 space-y-6">
                                    @forelse($smallArticles as $article)
                                        <div class="group flex gap-4 pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                            @php
                                                $defaultLogo = Config::get('app.logo') ? asset(Config::get('app.logo')) : asset('images/logo.png');
                                                $smallThumb = $article->thumbnail ? asset('storage/' . $article->thumbnail) : $defaultLogo;
                                            @endphp

                                            <a href="{{ route('news.show', $article->slug) }}"
                                            class="w-32 h-24 flex-shrink-0 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                                <img src="{{ $smallThumb }}" alt="{{ $article->title }}"
                                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                            </a>

                                            <div class="flex-1 min-w-0 space-y-2">
                                                <h5>
                                                    <a href="{{ route('news.show', $article->slug) }}"
                                                    class="text-base font-bold text-gray-900 hover:text-red-600 transition-colors line-clamp-2 leading-snug">
                                                        {{ $article->title }}
                                                    </a>
                                                </h5>

                                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                                    @if(!empty($article->author))
                                                        <span class="font-medium text-gray-700">{{ $article->author }}</span>
                                                        <span>•</span>
                                                    @endif
                                                    <span>{{ $article->published_at?->format('d M Y') }}</span>
                                                </div>

                                                @if($article->category->isNotEmpty())
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($article->category->take(2) as $cat)
                                                            <a href="{{ route('news.category.show', $cat->slug) }}"
                                                            class="text-xs text-red-600 hover:text-red-700 font-medium">
                                                                #{{ $cat->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full flex items-center justify-center py-10">
                                            <div class="text-center">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-gray-500 font-medium">Tidak ada berita yang tersedia</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Latest Videos --}}
                        <div class="border-t border-gray-100 p-2 md:p-8 bg-gradient-to-br from-gray-50 to-white">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="h-8 w-1.5 bg-gradient-to-b from-gray-900 to-gray-600 rounded-full"></div>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Video Terbaru</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @forelse($latestVideos as $videoPost)
                                    @php
                                        $defaultLogo = Config::get('app.logo') ? asset(Config::get('app.logo')) : asset('images/logo.png');
                                        $videoThumb = $defaultLogo;
                                        $caption = null;

                                        $contentBlocks = is_array($videoPost->content)
                                            ? $videoPost->content
                                            : (is_string($videoPost->content) ? json_decode($videoPost->content, true) : []);

                                        if (is_array($contentBlocks)) {
                                            foreach ($contentBlocks as $block) {
                                                if (($block['type'] ?? null) === 'video') {
                                                    $file = $block['data']['url'] ?? null;
                                                    $link = $block['data']['url_link'] ?? null;
                                                    $caption = $block['data']['caption'] ?? null;
                                                    $videoThumb = $file ? asset('storage/' . $file) : ($link ?? $defaultLogo);
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp

                                    <div class="group">
                                        <a href="{{ route('news.show', $videoPost->slug) }}"
                                        class="block relative overflow-hidden rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 mb-4">
                                            {{-- Category Badge --}}
                                            @if($videoPost->category->isNotEmpty())
                                                <div class="absolute top-3 left-3 z-20">
                                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white bg-red-600 rounded-lg shadow-lg">
                                                        {{ $videoPost->category->first()->name }}
                                                    </span>
                                                </div>
                                            @endif

                                            {{-- Play Button --}}
                                            <div class="absolute inset-0 flex items-center justify-center z-10">
                                                <div class="w-16 h-16 bg-black/70 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 group-hover:bg-red-600 transition-all duration-300">
                                                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            {{-- Video Thumbnail --}}
                                            <div class="aspect-video overflow-hidden bg-gray-100">
                                                @if(Str::contains($videoThumb, 'youtube.com') || Str::contains($videoThumb, 'youtu.be'))
                                                    @php
                                                        preg_match('/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]+)/', $videoThumb, $matches);
                                                        $videoId = $matches[1] ?? null;
                                                        $thumbnailUrl = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : $defaultLogo;
                                                    @endphp
                                                    <img src="{{ $thumbnailUrl }}" alt="{{ $videoPost->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                                                @elseif(Str::endsWith($videoThumb, ['.mp4', '.webm']))
                                                    <video class="w-full h-full object-cover" preload="metadata" disablepictureinpicture>
                                                        <source src="{{ $videoThumb }}#t=0.1" type="video/mp4">
                                                    </video>
                                                @else
                                                    <img src="{{ $defaultLogo }}" alt="{{ $videoPost->title }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        </a>

                                        <div class="space-y-3">
                                            <h5>
                                                <a href="{{ route('news.show', $videoPost->slug) }}"
                                                class="text-base md:text-lg font-bold text-gray-900 hover:text-red-600 transition-colors line-clamp-2 leading-snug">
                                                    {{ $videoPost->title }}
                                                </a>
                                            </h5>

                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                @if(!empty($videoPost->author))
                                                    <span class="font-medium text-gray-700">{{ $videoPost->author }}</span>
                                                    <span>•</span>
                                                @endif
                                                <span>{{ $videoPost->published_at?->format('d M Y') ?? 'Belum dipublikasikan' }}</span>
                                            </div>

                                            @if($caption)
                                                <p class="text-sm text-gray-600 line-clamp-2">{{ $caption }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 py-12 text-center">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Tidak ada video yang tersedia</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Sidebar --}}
                <aside class="w-full lg:w-2/5">
                    <div class="lg:sticky lg:top-24">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <x-layouts.rightsidesection />
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </x-layouts.main>

    {{-- Footer --}}
    <footer class="mt-10 py-6 text-center"></footer>
</x-layouts.app>
