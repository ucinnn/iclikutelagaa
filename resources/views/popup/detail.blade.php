<x-layouts.app>
    <div class="container mx-auto px-4 sm:px-6 py-12 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $popup->title }}</h1>

        @if($popup->image)
            <img src="{{ asset('storage/'.$popup->image) }}" 
                 alt="{{ $popup->title }}" 
                 class="w-full h-80 object-cover rounded-xl mb-8">
        @endif

        <div class="prose max-w-none text-lg text-gray-800">
            {!! html_entity_decode($popup->content) !!}
        </div>
    </div>
</x-layouts.app>
