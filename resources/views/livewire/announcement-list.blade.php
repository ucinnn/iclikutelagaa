<div 
    x-data="{ showModal: false }" 
    class="relative z-[100]"
>
    <!-- Tombol Bell -->
    <button 
        @click="showModal = !showModal" 
        type="button" 
        class="relative z-[1001] p-2 rounded-full hover:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <!-- Icon Bell -->
        <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 
                6.002 0 00-4-5.659V5a2 2 0 10-4 
                0v.341C7.67 6.165 6 8.388 6 11v3.159c0 
                .538-.214 1.055-.595 1.436L4 17h5m6 
                0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        <!-- Badge Notifikasi -->
        @php
            $unreadCount = $announcements->where('is_read', false)->count();
        @endphp
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 
                text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Modal dikirim langsung ke <body> -->
    <template x-teleport="body">
        <div 
            x-cloak
            x-show="showModal"
            @click.self="showModal = false"
            class="fixed inset-0 z-[9999] flex justify-end bg-black/40 backdrop-blur-sm"
            x-transition.opacity
        >
            <!-- Panel Geser dari Kanan -->
            <div 
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-full max-w-md h-full bg-white shadow-2xl border-l rounded-none flex flex-col z-[10000]"
            >
                <!-- Header -->
                <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between sticky top-0 z-[10001]">
                    <h3 class="text-sm font-semibold text-gray-700">📢 Pengumuman</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Daftar Pengumuman -->
                <div class="flex-1 overflow-y-auto">
                    @forelse($announcements as $announcement)
                        <div class="p-4 border-b hover:bg-gray-50 transition">
                            <div class="flex items-start gap-3">
                                <div 
                                    class="flex-shrink-0 w-1 h-16 rounded-full
                                    @if($announcement->type === 'info') bg-blue-500
                                    @elseif($announcement->type === 'success') bg-green-500
                                    @elseif($announcement->type === 'warning') bg-yellow-500
                                    @elseif($announcement->type === 'danger') bg-red-500
                                    @else bg-gray-300 @endif">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            {{ $announcement->title }}
                                            @if($announcement->is_pinned)
                                                <span class="ml-1">📌</span>
                                            @endif
                                        </h4>

                                        @if(!$announcement->is_read)
                                            <button 
                                                wire:click="markAsRead({{ $announcement->id }})"
                                                type="button"
                                                class="flex-shrink-0 px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                                Tandai Dibaca
                                            </button>
                                        @else
                                            <span class="flex-shrink-0 px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">
                                                ✓ Dibaca
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-600 whitespace-pre-line">
                                        {{ $announcement->body }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ \Carbon\Carbon::parse($announcement->published_at)->locale('id')->isoFormat('D MMM Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            Tidak ada pengumuman
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </template>
</div>
