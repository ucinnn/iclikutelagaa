<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Announcement;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use FIlament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    public function index()
    {
        try {
            // Ambil role user
            $user = Auth::user();
            $userRole = $user->role ?? 'user'; // Sesuaikan dengan field role Anda

            // Query announcements
            $announcements = Announcement::query()
                ->where('status', 'published')
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->where(function ($query) use ($userRole) {
                    $query->whereNull('target_roles')
                        ->orWhereJsonContains('target_roles', $userRole);
                })
                ->orderBy('is_pinned', 'desc')
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();

            // Tambahkan property is_read ke setiap announcement
            $announcements->each(function ($announcement) use ($user) {
                $announcement->is_read = $announcement->reads()
                    ->where('user_id', $user->id)
                    ->exists();
            });

            $unreadCount = $announcements->where('is_read', false)->count();

            return response()->json([
                'announcements' => $announcements,
                'unreadCount' => $unreadCount,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Announcement fetch error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'announcements' => [],
                'unreadCount' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $userId = Auth::id();

            // Cek apakah sudah dibaca
            $exists = $announcement->reads()->where('user_id', $userId)->exists();

            if (!$exists) {
                $announcement->reads()->attach($userId, ['read_at' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Announcement marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendNotification()
    {
        // Kirim ke user yang sedang login
        Notification::make()
            ->title('Pengumuman Baru')
            ->body('Ada pengumuman penting untuk Anda.')
            ->icon('heroicon-o-bell')
            ->iconColor('success')
            ->sendToDatabase(Filament::auth()->user());

        return back()->with('success', 'Notifikasi terkirim!');
    }

    public function broadcast()
    {
        // Kirim ke semua users
        $users = User::all();

        Notification::make()
            ->title('Notifikasi Broadcast')
            ->body('Pesan untuk semua user.')
            ->icon('heroicon-o-megaphone')
            ->iconColor('warning')
            ->sendToDatabase($users);

        return back()->with('success', 'Broadcast terkirim ke semua user!');
    }
}
