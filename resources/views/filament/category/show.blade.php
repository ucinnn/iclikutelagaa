<x-layouts.app>
@section('pageTitle', 'Berita dengan Category: ' . $category->name)


@php
    // Fungsi helper untuk mendapatkan thumbnail
    function getArticleThumbnail($article, $default = null) {
        // 1. Cek field thumbnail
        if (!empty($article->thumbnail)) {
            return asset('storage/' . $article->thumbnail);
        }

        // 2. Cek konten JSON / EditorJS
        $content = $article->content;

        // Decode JSON jika masih berupa string
        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $content = $decoded;
            }
        }

        // 3. Ambil gambar dari blok image
        if (is_array($content)) {
            foreach ($content as $block) {
                if (isset($block['type']) && $block['type'] === 'image') {
                    $imageUrl = $block['data']['url_link'] ?? $block['data']['url'] ?? null;

                    if ($imageUrl) {
                        // Jika bukan URL absolut, tambahkan path storage
                        if (!str_starts_with($imageUrl, 'http')) {
                            return asset('storage/' . $imageUrl);
                        }
                        return $imageUrl;
                    }
                }
            }
        }

        // 4. Jika konten HTML berisi tag <img>
        if (is_string($content) && preg_match('/<img[^>]+src="([^">]+)"/i', $content, $matches)) {
            return $matches[1];
        }

        // 5. Fallback ke gambar default
        return $default ?? asset('images/logo.png');
    }
@endphp

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Berita dengan kategori: {{ $category->name }}</h1>
        @if($category->keterangan)
            <p class="text-gray-600">{{ $category->keterangan }}</p>
        @endif
    </div>

    @if($news->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500">Belum ada berita dalam kategori ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    {{-- ✅ Gunakan helper getArticleThumbnail --}}
                    @php
                        $thumbnail = getArticleThumbnail($item);
                    @endphp

                    <img src="{{ $thumbnail }}" 
                         alt="{{ $item->title }}"
                         class="w-full h-48 object-cover">

                    <div class="p-4">
                        <div class="flex gap-2 mb-2">
                            @foreach($item->category as $cat)
                                <a href="{{ route('news.category.show', $cat->slug) }}"
                                   class="text-xs uppercase bg-custom-red text-white px-2 py-1 rounded-sm hover:bg-red-700">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                        
                        <h2 class="text-xl font-bold mb-2 hover:text-custom-red transition">
                            <a href="{{ route('news.show', $item->slug) }}">
                                {{ $item->title }}
                            </a>
                        </h2>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <span>{{ $item->author }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $item->published_at->format('d M Y') }}</span>
                        </div>
                        
                        <a href="{{ route('news.show', $item->slug) }}" 
                           class="text-custom-red hover:underline">
                            Baca selengkapnya →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $news->links() }}
        </div>
    @endif
</div>
</x-layouts.app>
