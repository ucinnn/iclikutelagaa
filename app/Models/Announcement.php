<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'type',
        'icon',
        'status',
        'published_at',
        'expires_at',
        'target_roles',
        'is_pinned',
        'views',
        'created_by',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_pinned' => 'boolean',
        'views' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            // Tambahkan type hint untuk Intelephense
            /** @var \Illuminate\Contracts\Auth\Guard $auth */
            $auth = auth();

            if ($auth->check()) {
                $announcement->created_by = $auth->id();
            }
        });
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function readers()
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
            ->withPivot('read_at')
            ->withTimestamps();
    }
    // Relasi dengan users yang sudah membaca
    // Relasi dengan users yang sudah membaca
    public function reads()
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
            ->withPivot('read_at');
    }

    // Cek apakah user sudah membaca
    public function isReadBy($userId = null)
    {
        $userId = $userId ?? Auth::id();
        return $this->reads()->where('user_id', $userId)->exists();
    }

    // Mark as read untuk user tertentu
    public function markAsReadBy($userId = null)
    {
        $userId = $userId ?? Auth::id();

        if (!$this->isReadBy($userId)) {
            $this->reads()->attach($userId, ['read_at' => now()]);
        }
    }

    // Scopes

    // Scope untuk announcement yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    // Scope untuk role tertentu
    public function scopeForRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_roles')
                ->orWhereJsonContains('target_roles', $role);
        });
    }
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->whereNull('target_roles')
                ->orWhere(function ($subQ) use ($user) {
                    foreach ($user->roles as $role) {
                        $subQ->orWhereJsonContains('target_roles', $role);
                    }
                });
        });
    }

    // Helper Methods


    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getTypeColor(): string
    {
        return match ($this->type) {
            'info' => 'info',
            'success' => 'success',
            'warning' => 'warning',
            'danger' => 'danger',
            default => 'gray',
        };
    }

    public function getTypeIcon(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match ($this->type) {
            'info' => 'heroicon-o-information-circle',
            'success' => 'heroicon-o-check-circle',
            'warning' => 'heroicon-o-exclamation-triangle',
            'danger' => 'heroicon-o-x-circle',
            default => 'heroicon-o-megaphone',
        };
    }
}
