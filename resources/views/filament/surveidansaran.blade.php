<x-layouts.app>
    @section('pageTitle', 'Survey')

    <section class="bg-gradient-to-br from-indigo-600 to-blue-600 text-white py-16 sm:py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold mb-4">Sumber Daya & Formulir Eksternal</h1>
            <p class="text-blue-100 max-w-2xl mx-auto">
                Berikut daftar sumber daya eksternal dan formulir yang bisa Anda akses.
            </p>
        </div>
    </section>

    <x-layouts.main>
        <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($survey as $res)
                    <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-xl transition duration-300">
                        <div class="flex items-center mb-4">
                            @if($res->icon)
                                <i class="{{ $res->icon }} text-blue-600 text-3xl mr-3"></i>
                            @else
                                <i class="fas fa-external-link-alt text-blue-600 text-3xl mr-3"></i>
                            @endif
                            <h3 class="font-bold text-lg text-gray-800">{{ $res->title }}</h3>
                        </div>
                        @if($res->description)
                            <div class="text-gray-600 text-sm mb-5">
                                {!! $res->description !!}
                            </div>
                        @endif
                        <a href="{{ $res->link }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-link mr-2"></i> Kunjungi
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-info-circle text-4xl text-gray-400 mb-3"></i>
                        <h3 class="text-lg font-semibold text-gray-600">Belum ada data sumber daya</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </x-layouts.main>
</x-layouts.app>
