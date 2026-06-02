<div
    @if ($pollingInterval)
        wire:poll.{{ $pollingInterval }}
    @endif
    class="flex"
>
    @if ($trigger)
        <x-filament-notifications::database.trigger>
            {{ $trigger->with(['unreadNotificationsCount' => $unreadNotificationsCount]) }}
        </x-filament-notifications::database.trigger>
    @endif

    <x-filament-notifications::database.modal
        :notifications="$notifications"
        :unread-notifications-count="$unreadNotificationsCount"
    />

    @if ($broadcastChannel)
        <x-filament-notifications::database.echo :channel="$broadcastChannel" />
    @endif
</div>
