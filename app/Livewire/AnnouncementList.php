<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementList extends Component
{
    public $announcements;

    public function mount()
    {
        $this->loadAnnouncements();
    }

    public function loadAnnouncements()
    {
        // Menggunakan Spatie Permission
        $userRole = Auth::user()->roles->name ?? 'user';

        $this->announcements = Announcement::query()
            ->where(function ($query) use ($userRole) {
                $query->whereNull('target_roles')
                    ->orWhereJsonContains('target_roles', $userRole);
            })
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function markAsRead($announcementId)
    {
        $announcement = Announcement::find($announcementId);

        if ($announcement) {
            $announcement->update(['is_read' => true]);
            $this->loadAnnouncements();
        }
    }

    public function render()
    {
        return view('livewire.announcement-list');
    }
}
