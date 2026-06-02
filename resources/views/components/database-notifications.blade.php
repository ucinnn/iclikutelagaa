@livewire('database-notifications')
<div wire:poll.30s="loadNotifications" class="relative">
    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 z-50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                Notifikasi
            </h3>

            @if($unreadNotificationsCount > 0)
                <button
                    wire:click="markAllAsRead"
                    class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400"
                >
                    Tandai semua dibaca
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div
                    wire:key="notification-{{ $notification->id }}"
                    class="border-b border-gray-200 px-4 py-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 {{ is_null($notification->read_at) ? 'bg-primary-50 dark:bg-primary-950/10' : '' }}"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-950 dark:text-white">
                                {{ data_get($notification->data, 'title', 'Notifikasi') }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ data_get($notification->data, 'body', 'Anda memiliki notifikasi baru') }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if(is_null($notification->read_at))
                            <button
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="shrink-0 rounded-full p-1 text-primary-600 hover:bg-primary-100 dark:text-primary-400 dark:hover:bg-primary-950"
                                title="Tandai dibaca"
                            >
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <x-filament::icon
                        icon="heroicon-o-bell-slash"
                        class="mx-auto h-8 w-8 text-gray-400"
                    />
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada notifikasi
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
            <div class="border-t border-gray-200 px-4 py-2 dark:border-gray-700">
                <a
                    href="{{ route('filament.admin.pages.notifications') }}"
                    class="block text-center text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400"
                >
                    Lihat semua notifikasi
                </a>
            </div>
        @endif
    </div></div>
