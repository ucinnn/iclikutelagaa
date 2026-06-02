<?php

namespace App\Http\Controllers;

use App\Models\HelpdeskMessage;
use App\Mail\HelpdeskTicketCreated;
use App\Mail\HelpdeskTicketReplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HelpdeskController extends Controller
{
    /**
     * Mapping topik ke env key email departemen
     */
    protected array $departmentEmailMap = [
        'HR'   => 'HELPDESK_EMAIL_HR',
        'HSE'  => 'HELPDESK_EMAIL_HSE',
        'Umum' => 'HELPDESK_EMAIL_UMUM',
    ];

    public function index()
    {
        $messages = HelpdeskMessage::with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('filament.helpdesk.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message'   => 'required|string|max:5000',
            'subject'   => 'required_without:parent_id|string|max:255',
            'parent_id' => 'nullable|exists:helpdesk_messages,id',
        ]);

        // ── Balasan user ke ticket yang sudah ada ─────────────────────────
        if ($request->filled('parent_id')) {
            $parentThread = HelpdeskMessage::findOrFail($request->parent_id);

            if ($parentThread->status === 'closed') {
                return back()->with('error', 'Ticket ini sudah ditutup. Anda tidak dapat membalas lagi.');
            }

            if ($parentThread->user_id !== Auth::id()) {
                return back()->with('error', 'Anda tidak memiliki akses ke ticket ini.');
            }

            HelpdeskMessage::create([
                'user_id'        => Auth::id(),
                'parent_id'      => $parentThread->id,
                'subject'        => null,
                'message'        => $request->message,
                'is_admin_reply' => false,
                'status'         => $parentThread->status,
            ]);

            $parentThread->update(['is_replied' => false]);

            // Kirim email notifikasi ke departemen (admin)
            $this->notifyAdmin($parentThread, $request->message);

            return back()->with('success', 'Balasan Anda berhasil dikirim.');
        }

        // ── Buat ticket baru ──────────────────────────────────────────────
        $ticket = HelpdeskMessage::create([
            'user_id'        => Auth::id(),
            'parent_id'      => null,
            'subject'        => $request->subject,
            'message'        => $request->message,
            'is_admin_reply' => false,
            'status'         => 'open',
            'is_replied'     => false,
        ]);

        // 1. Kirim email konfirmasi ke user
        $this->notifyUserTicketCreated($ticket);

        // 2. Kirim email notifikasi ke departemen terkait
        $this->sendNewTicketNotification($ticket);

        return redirect()->route('helpdesk.index')
            ->with('success', 'Ticket berhasil dibuat! Konfirmasi telah dikirim ke email Anda.');
    }

    // ── Private Methods ───────────────────────────────────────────────────

    /**
     * Email konfirmasi ke user saat ticket baru berhasil dibuat
     */
    private function notifyUserTicketCreated($ticket): void
    {
        try {
            if (empty($ticket->user?->email)) return;

            Mail::to($ticket->user->email)
                ->send(new HelpdeskTicketCreated($ticket, 'user'));

        } catch (\Exception $e) {
            Log::error('Helpdesk email konfirmasi ke user gagal: ' . $e->getMessage());
        }
    }

    /**
     * Email notifikasi ke departemen saat ticket baru masuk
     * (menggantikan sendNewTicketNotification lama)
     */
    private function sendNewTicketNotification($ticket): void
    {
        try {
            // Kirim ke email departemen spesifik (HR/HSE/Umum) via env
            $envKey = $this->departmentEmailMap[$ticket->subject] ?? null;

            if ($envKey && !empty(env($envKey))) {
                $emails = array_filter(array_map('trim', explode(',', env($envKey))));
                Mail::to($emails)->send(new HelpdeskTicketCreated($ticket, 'department'));
            }

            // Fallback: selalu kirim juga ke APP_SITEMAIL (email utama admin)
            if (!empty(env('APP_SITEMAIL'))) {
                Mail::to(env('APP_SITEMAIL'))
                    ->send(new HelpdeskTicketCreated($ticket, 'department'));
            }

        } catch (\Exception $e) {
            Log::error('Helpdesk email notifikasi ke departemen gagal: ' . $e->getMessage());
        }
    }

    /**
     * Email notifikasi ke departemen saat user membalas ticket
     * (menggantikan notifyAdmin lama)
     */
    private function notifyAdmin($parentThread, string $message): void
    {
        try {
            // Buat object reply sementara untuk Mailable
            $reply                = new \stdClass();
            $reply->message       = $message;
            $reply->is_admin_reply = false;
            $reply->created_at    = now();

            // Kirim ke email departemen spesifik
            $envKey = $this->departmentEmailMap[$parentThread->subject] ?? null;

            if ($envKey && !empty(env($envKey))) {
                $emails = array_filter(array_map('trim', explode(',', env($envKey))));
                Mail::to($emails)->send(new HelpdeskTicketReplied($parentThread, $reply));
            }

            // Fallback: kirim ke APP_SITEMAIL
            if (!empty(env('APP_SITEMAIL'))) {
                Mail::to(env('APP_SITEMAIL'))
                    ->send(new HelpdeskTicketReplied($parentThread, $reply));
            }

        } catch (\Exception $e) {
            Log::error('Helpdesk email notifyAdmin gagal: ' . $e->getMessage());
        }
    }
}