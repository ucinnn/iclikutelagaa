<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\HelpdeskMessage;
use Illuminate\Support\Facades\Auth;

class Helpdeskchat extends Component
{
    // Jika mau, bisa simpan ticket yang dipilih
    public $selectedTicketId;

    // Computed property untuk ambil semua thread utama + replies
    public function getMessagesProperty()
    {
        return HelpdeskMessage::with(['user', 'replies.user'])
            ->whereNull('parent_id')          // hanya thread utama
            ->where('user_id', Auth::id())    // hanya milik user login
            ->orderByDesc('created_at')
            ->get();
    }

    public function render()
    {
        return view('filament.forms.component.conversation-thread'); // Blade Livewire
    }
}
