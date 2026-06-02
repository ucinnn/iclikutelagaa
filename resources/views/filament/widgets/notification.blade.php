<x-filament::widget>
    <x-filament::card class="flex items-center justify-end space-x-4">
        @php
            $user = Auth::user();
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();
        @endphp

        <x-filament::dropdown placement="bottom-end">
            <x-slot name="trigger">
                <button class="relative">
                    <x-heroicon-o-bell class="w-6 h-6 text-gray-600" />
                    @if ($notifications->count() > 0)
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
            </x-slot>

            <x-filament::dropdown.list>
                @forelse ($notifications as $notification)
                    <x-filament::dropdown.list.item>
                        <div class="text-sm text-gray-700">
                            {{ $notification->data['message'] ?? 'No message' }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </div>
                    </x-filament::dropdown.list.item>
                @empty
                    <x-filament::dropdown.list.item>
                        <span class="text-sm text-gray-500">No new notifications</span>
                    </x-filament::dropdown.list.item>
                @endforelse
            </x-filament::dropdown.list>
        </x-filament::dropdown>
    </x-filament::card>
</x-filament::widget>
