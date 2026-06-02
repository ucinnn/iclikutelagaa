<x-layouts.app>
    @section('pageTitle', 'Semua Pop-up Berita')

    <div class="container mx-auto px-4 sm:px-6 py-12">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-10 text-center">
            Semua Pop-up Berita Aktif
        </h1>

        @if($popups->isEmpty())
            <p class="text-center text-gray-600 text-lg">Tidak ada pop-up berita aktif saat ini.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popups as $item)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition overflow-hidden flex flex-col">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-48 object-cover">
                        @endif

                        <div class="p-6 flex-1 flex flex-col">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3 line-clamp-2">
                                {{ $item->title }}
                            </h2>

                            <div class="text-gray-700 text-sm leading-relaxed mb-4 prose max-w-none">
                                {!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($item->content)), 200) !!}
                            </div>

                            <div class="mt-auto">
                                <button 
                                    onclick="showPopupDetail({{ $item->id }})"
                                    class="inline-block bg-custom-red text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-red-600 transition"
                                >
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function showPopupDetail(id) {
            window.location.href = `/popup/${id}`;
        }
    </script>
</x-layouts.app>
