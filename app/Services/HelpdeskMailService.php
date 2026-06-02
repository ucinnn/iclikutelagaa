<?php

namespace App\Services;

use App\Mail\HelpdeskTicketCreated;
use App\Mail\HelpdeskTicketReplied;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HelpdeskMailService
{
    protected array $departmentEmailMap = [
        'HR'   => 'HELPDESK_EMAIL_HR',
        'HSE'  => 'HELPDESK_EMAIL_HSE',
        'Umum' => 'HELPDESK_EMAIL_UMUM',
    ];

    public function notifyUserTicketCreated($ticket): void
    {
        try {
            if (empty($ticket->user?->email)) return;

            Mail::to($ticket->user->email)
                ->send(new HelpdeskTicketCreated($ticket, 'user'));

        } catch (\Exception $e) {
            Log::error('Helpdesk email to user failed: ' . $e->getMessage());
        }
    }

    public function notifyDepartmentTicketCreated($ticket): void
    {
        try {
            $envKey = $this->departmentEmailMap[$ticket->subject] ?? null;

            if (!$envKey) return;

            $departmentEmail = env($envKey);

            if (empty($departmentEmail)) {
                Log::warning("Helpdesk: env key {$envKey} tidak diset.");
                return;
            }

            // Support multiple email dipisah koma
            $emails = array_filter(array_map('trim', explode(',', $departmentEmail)));

            Mail::to($emails)
                ->send(new HelpdeskTicketCreated($ticket, 'department'));

        } catch (\Exception $e) {
            Log::error('Helpdesk email to department failed: ' . $e->getMessage());
        }
    }

    public function notifyUserTicketReplied($ticket, $reply): void
    {
        try {
            if (empty($ticket->user?->email)) return;
            if (!$reply->is_admin_reply) return;

            Mail::to($ticket->user->email)
                ->send(new HelpdeskTicketReplied($ticket, $reply));

        } catch (\Exception $e) {
            Log::error('Helpdesk reply email to user failed: ' . $e->getMessage());
        }
    }
}