<?php

namespace App\Filament\Widgets;

use App\Models\HelpdeskMessage;
use Filament\Widgets\Widget;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HelpdeskWidget extends Widget
{
    protected static string $view = 'filament.widgets.helpdesk-stat-widget';

    protected static ?string $pollingInterval = '30s';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        // Total messages (main tickets only, not replies)
        $totalMessages = HelpdeskMessage::whereNull('parent_id')->count();

        // Open and closed tickets
        $openMessages = HelpdeskMessage::where('status', 'open')
            ->whereNull('parent_id')
            ->count();

        $closedMessages = HelpdeskMessage::where('status', 'closed')
            ->whereNull('parent_id')
            ->count();

        // Response rate (tickets with at least one reply)
        $messagesWithReplies = HelpdeskMessage::whereNull('parent_id')
            ->has('replies')
            ->count();

        $responseRate = $totalMessages > 0
            ? round(($messagesWithReplies / $totalMessages) * 100, 1)
            : 0;

        // Average response time (in hours)
        $avgResponseTime = HelpdeskMessage::whereNull('parent_id')
            ->whereHas('replies', function ($query) {
                $query->where('is_admin_reply', true);
            })
            ->get()
            ->map(function ($ticket) {
                $firstReply = $ticket->replies()
                    ->where('is_admin_reply', true)
                    ->orderBy('created_at')
                    ->first();

                if ($firstReply) {
                    return $ticket->created_at->diffInHours($firstReply->created_at);
                }
                return null;
            })
            ->filter()
            ->average();

        // Recent open tickets with user info
        $recentTickets = HelpdeskMessage::with(['user', 'replies'])
            ->whereNull('parent_id')
            ->where('status', 'open')
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'message' => $ticket->message,
                    'user_name' => $ticket->user->name ?? 'Unknown User',
                    'user_email' => $ticket->user->email ?? '',
                    'replies_count' => $ticket->replies->count(),
                    'has_admin_reply' => $ticket->replies->where('is_admin_reply', true)->isNotEmpty(),
                    'created_at' => $ticket->created_at,
                    'created_at_human' => $ticket->created_at->diffForHumans(),
                ];
            });

        // Messages per day (last 7 days)
        $messagesPerDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $messagesPerDay[] = HelpdeskMessage::whereNull('parent_id')
                ->whereDate('created_at', $date)
                ->count();
        }

        // Tickets by status today
        $todayOpen = HelpdeskMessage::whereNull('parent_id')
            ->where('status', 'open')
            ->whereDate('created_at', today())
            ->count();

        $todayClosed = HelpdeskMessage::whereNull('parent_id')
            ->where('status', 'closed')
            ->whereDate('updated_at', today())
            ->count();

        return [
            'totalMessages' => $totalMessages,
            'openMessages' => $openMessages,
            'closedMessages' => $closedMessages,
            'responseRate' => $responseRate,
            'avgResponseTime' => $avgResponseTime ? round($avgResponseTime, 1) : 0,
            'recentTickets' => $recentTickets,
            'messagesPerDay' => $messagesPerDay,
            'todayOpen' => $todayOpen,
            'todayClosed' => $todayClosed,
        ];
    }
    protected function getData(): array
    {
        return [
            'totalMessages' => HelpdeskMessage::whereNull('parent_id')->count(),
            'openMessages' => HelpdeskMessage::where('status', 'open')->whereNull('parent_id')->count(),
            'closedMessages' => HelpdeskMessage::where('status', 'closed')->whereNull('parent_id')->count(),
            'responseRate' => 85,
            'avgResponseTime' => 2.4,
            'todayOpen' => HelpdeskMessage::whereDate('created_at', today())->where('status', 'open')->count(),
            'todayClosed' => HelpdeskMessage::whereDate('updated_at', today())->where('status', 'closed')->count(),
            'messagesPerDay' => collect(range(6, 0))
                ->map(fn($i) => HelpdeskMessage::whereDate('created_at', now()->subDays($i))->whereNull('parent_id')->count())
                ->values()
                ->toArray(),

            'recentTickets' => HelpdeskMessage::whereNull('parent_id')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($msg) => [
                    'id' => $msg->id,
                    'subject' => $msg->subject,
                    'message' => Str::limit($msg->message, 100),
                    'user_name' => $msg->user->name ?? 'Unknown',
                    'user_email' => $msg->user->email ?? '-',
                    'created_at_human' => $msg->created_at->diffForHumans(),
                    'replies_count' => $msg->replies()->count(),
                    'has_admin_reply' => $msg->replies()->where('is_admin_reply', true)->exists(),
                ]),
        ];
    }
}
