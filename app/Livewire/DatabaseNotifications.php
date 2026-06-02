<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\User;
use Filament\Facades\Filament;

class DatabaseNotifications extends Component
{
    public int $unreadNotificationsCount = 0;
    public Collection $notifications;
    public bool $showDropdown = false;

    protected $listeners = ['notificationSent' => 'loadNotifications'];

    public function mount(): void
    {
        $this->notifications = collect();
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        if ($user) {
            $this->notifications = $user->notifications()
                ->latest()
                ->take(10)
                ->get();

            $this->unreadNotificationsCount = $user->unreadNotifications()->count();
        }
    }

    public function markAsRead(string $notificationId): void
    {
        $notification = DatabaseNotification::find($notificationId);

        if ($notification && $notification->notifiable_id === Filament::auth()->id()) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead(): void
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        return view('livewire.database-notifications');
    }
}
