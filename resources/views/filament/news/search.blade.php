<x-layouts.app>
    @section('pageTitle', 'Hasil Pencarian')

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">
            Hasil pencarian untuk:
            <span class="text-custom-red">"{{ $query }}"</span>
        </h2>

        @if($news->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($news as $item)
                    @php
                        $thumb = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : asset('images/logo.png');

                        // Ambil isi konten bersih dari JSON atau string biasa
                        $content = $item->content;

                        if (is_array($content)) {
                            // Jika disimpan dalam array JSON
                            $content = collect($content)
                                ->pluck('data.text')
                                ->implode(' ');
                        } elseif (is_string($content) && str_starts_with($content, '[')) {
                            // Jika JSON string
                            $decoded = json_decode($content, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $content = collect($decoded)
                                    ->pluck('data.text')
                                    ->implode(' ');
                            }
                        }

                        // Bersihkan tag HTML dan batasi teks
                        $content = Str::limit(strip_tags($content), 200);
                    @endphp

                    <div class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                        <a href="{{ route('news.show', $item->slug) }}">
                            <img src="{{ $thumb }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                        </a>

                        <div class="p-4">
                            {{-- Kategori --}}
                            @if($item->category->isNotEmpty())
                                <div class="mb-2">
                                    @foreach($item->category as $cat)
                                        <a href="{{ route('news.category.show', $cat->slug) }}"
                                           class="inline-block text-xs uppercase bg-custom-red text-white px-2 py-1 rounded-sm font-semibold mr-1 hover:bg-red-700 transition">
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Judul --}}
                            <h3 class="text-lg font-semibold mb-2">
                                <a href="{{ route('news.show', $item->slug) }}"
                                   class="hover:text-custom-red transition line-clamp-2">
                                    {{ $item->title }}
                                </a>
                            </h3>

                            {{-- Deskripsi ringkas --}}
                            <p class="text-sm text-gray-600 line-clamp-3">
                                {{ $content }}
                            </p>

                            {{-- Penulis & tanggal --}}
                            <div class="text-xs text-gray-500 mt-2">
                                {{ $item->user->name ?? 'Tidak diketahui' }}<br>
                                {{ $item->published_at ? $item->published_at->format('d M Y, H:i') : '—' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $news->links() }}
            </div>
        @else
            <p class="text-gray-500 mt-6">
                Tidak ditemukan berita yang cocok dengan kata kunci tersebut.
            </p>
        @endif
    </div>
</x-layouts.app>
