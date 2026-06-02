<div class="w-full lg:w-full px-2">
    <div class="lg:pl-6 pb-10">

        {{-- ===================== --}}
        {{-- 📈 Most Popular --}}
        {{-- ===================== --}}
        @php
            use App\Models\News;
            $mostPopular = News::where('status', 'published')
                ->orderByDesc('views')
                ->take(5)
                ->get();
        @endphp

        <div class="pb-14">
            <div class="flex items-center border-b-2 border-gray-700 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 uppercase">Berita Terpopuler</h3>
            </div>

            <ul class="pt-4 space-y-5">
                @forelse($mostPopular as $index => $item)
                <a href="{{ route('news.show', $item->slug) }}"
                               class="block text-sm font-semibold text-gray-800 hover:text-custom-red transition-colors leading-relaxed">
                    <li class="flex items-start space-x-3">
                        {{-- Nomor urut --}}
                        <div
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-white font-bold text-lg">
                            {{ $index + 1 }}
                        </div>

                        {{-- Detail berita --}}
                        <div class="flex-1">

                                {{ $item->title }}

                            <div class="text-xs text-gray-600 mt-1 space-x-2">
                                <span>👤 {{ $item->author ?? 'Unknown' }}</span>
                                <span>•</span>
                                <span>🗓️ {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</span>
                                <span>•</span>
                                <span>👁️ {{ number_format($item->views) }} views</span>
                            </div>
                        </div>
                    </li>
                </a>
                @empty
                    <li class="text-gray-500 text-sm">Belum ada berita populer.</li>
                @endforelse
            </ul>
        </div>

        {{-- ===================== --}}
        {{-- 🎬 Featured Video --}}
        {{-- ===================== --}}
        <div class="pb-14">
            <x-layouts.featuredvideo />
        </div>

        {{-- ===================== --}}
        {{-- 🏷️ Tags --}}
        {{-- ===================== --}}
        <div class="pb-10">
            <div class="flex items-center border-b-2 border-gray-700 pb-2 mb-6">
                <h3 class="text-xl font-bold text-gray-800 uppercase">Tags</h3>
            </div>

            @php
                $tags = \App\Models\Tags::all();
            @endphp

            <div class="flex flex-wrap -m-1.5">
                @forelse($tags as $tag)
                    <a href="{{ route('news.tags.index', $tag->slug) }}"
                       class="flex items-center justify-center h-8 border border-gray-300 rounded-full text-xs text-gray-600 hover:bg-gray-700 hover:text-white hover:border-gray-700 transition-all px-4 py-1 m-1.5">
                        {{ $tag->name }}
                    </a>
                @empty
                    <p class="text-sm text-gray-400">Belum ada tag tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
