<div wire:poll.5s class="relative">
    {{-- Bell Icon --}}
    <button wire:click="$toggle('showDropdown')" class="relative">
        <x-filament::icon-button
            :badge="$unreadNotificationsCount ?: null"
            color="gray"
            icon="heroicon-o-bell"
            icon-size="lg"
            class="fi-topbar-database-notifications-btn"
        />
    </button>

    {{-- Dropdown --}}
    @if ($showDropdown ?? false)
        <div class="absolute right-0 mt-2 w-80 bg-white shadow-xl rounded-lg z-50">
            <div class="p-4 font-bold border-b">Notifikasi</div>
            <ul class="max-h-60 overflow-y-auto divide-y">
                @forelse ($notifications as $notification)
                    <li class="p-3 hover:bg-gray-100">
                        {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                        <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                    </li>
                @empty
                    <li class="p-3 text-gray-500">Tidak ada notifikasi.</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
