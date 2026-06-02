<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Filament\Facades\Filament;

class TopbarNotifications extends Component
{

    public bool $showDropdown = false;

    public function render()
    {
        $user = Filament::auth()->user();

        return view('livewire.topbar-notification', [
            'notifications' => $user->notifications()->latest()->take(10)->get(),
            'unreadNotificationsCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
