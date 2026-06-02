<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementRead extends Model
{
    protected $fillable = [
        'announcement_id',
        'user_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public $timestamps = false; // karena kita cuma pakai read_at

    /**
     * Announcement terkait
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    /**
     * User yang membaca
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
