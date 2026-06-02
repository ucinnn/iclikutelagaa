<x-layouts.app>

    @section('pageTitle', content: 'Berita dengan Tag: ' . $tag->name)

    <x-layouts.main>
        <div class="w-full max-w-[1600px] mx-auto px-6 lg:px-12 py-8 grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- Daftar Berita Berdasarkan Tag --}}
            <section class="lg:col-span-3 bg-white rounded-xl shadow-sm p-6">
                <h1 class="text-2xl font-bold mb-4">
                    Berita dengan Tag: <span class="text-red-600">{{ $tag->name }}</span>
                </h1>

                {{-- Search --}}
                <form method="GET" class="mb-6">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="border border-gray-300 rounded-full px-4 py-2 w-full md:w-1/2 text-sm focus:ring focus:ring-red-200 focus:border-red-500"
                        placeholder="Cari berita..."
                    >
                </form>

                {{-- Daftar Berita --}}
                @if($news->count())
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($news as $item)
                            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition bg-white">
                                <a href="{{ route('news.show', $item->slug) }}">
                                    <img
                                        src="{{ $item->image_url }}"
                                        alt="{{ $item->title }}"
                                        class="w-full h-48 object-cover"
                                    >
                                    <div class="p-4">
                                        <h2 class="text-lg font-semibold mb-2 line-clamp-2 hover:text-red-600 transition">
                                            {{ $item->title }}
                                        </h2>
                                        <p class="text-gray-600 text-sm line-clamp-3">
                                            {{ Str::limit(strip_tags(is_array($item->content) ? ($item->content['id'] ?? '') : $item->content), 100) }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">
                                            Dipublikasikan: {{ optional($item->published_at)->translatedFormat('d M Y') }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $news->links() }}
                    </div>
                @else
                    <p class="text-gray-500">Belum ada berita dengan tag ini.</p>
                @endif
            </section>

        </div>
    </x-layouts.main>

</x-layouts.app>
