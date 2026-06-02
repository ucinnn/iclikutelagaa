<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HelpdeskMessage extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'subject',
        'message',
        'status',
        'is_admin_reply',
        'is_replied',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
        'is_replied'     => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(HelpdeskMessage::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(HelpdeskMessage::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Scope untuk mengambil hanya thread utama (bukan reply)
    public function scopeMainThreads($query)
    {
        return $query->whereNull('parent_id');
    }

    // Helper untuk cek apakah ada balasan admin
    public function hasAdminReply(): bool
    {
        return $this->replies()->where('is_admin_reply', true)->exists();
    }

    // Helper untuk ambil balasan admin terakhir
    public function latestAdminReply()
    {
        return $this->replies()->where('is_admin_reply', true)->latest()->first();
    }
}