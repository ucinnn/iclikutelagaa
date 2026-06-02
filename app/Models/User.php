<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserRegisteredEmail;
use App\Filament\Resources\UserResource;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRoles;


class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    const ROLE_ADMIN = "admin";
    const ROLE_AUTHOR = "author";
    const ROLE_USER = "user";

    const ROLES = [
        self::ROLE_ADMIN => 'admin',
        self::ROLE_AUTHOR => 'author',
        self::ROLE_USER => 'user',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NIK',
        'name',
        'role',
        'email',
        'password',
        'created_by',
        'updated_by',
        'receive_notifications',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'roles' => 'array', // Cast ke array
            'receive_notifications' => 'boolean',
        ];
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? []);
    }

    public function hasAnyRole(array $roles): bool
    {
        return !empty(array_intersect($roles, $this->roles ?? []));
    }

    public function assignRole(string $role): void
    {
        $roles = $this->roles ?? [];
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->update(['roles' => $roles]);
        }
    }

    public function removeRole(string $role): void
    {
        $roles = $this->roles ?? [];
        $this->update(['roles' => array_values(array_diff($roles, [$role]))]);
    }

    // Scope untuk query
    public function scopeWithRole($query, string $role)
    {
        return $query->whereJsonContains('roles', $role);
    }

    public function scopeWithAnyRole($query, array $roles)
    {
        return $query->where(function ($q) use ($roles) {
            foreach ($roles as $role) {
                $q->orWhereJsonContains('roles', $role);
            }
        });
    }


    public function scopeReceivingNotifications($query)
    {
        return $query->where('receive_notifications', true);
    }

    // Relasi dengan News
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_users', 'users_id', 'news_id')
            ->withTimestamps();
    }

    /**
     * The attributes that should be hidden for arrays and JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }



    /**
     * Boot the model and auto-fill created_by / updated_by.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (self::where('email', $model->email)->orWhere('NIK', $model->NIK)->exists()) {
                Log::warning("⚠️ Duplikasi pembuatan user dicegah: {$model->email}");
                return false; // menghentikan proses insert
            }
            if (Filament::auth()->user()) {
                $user = Filament::auth()->user();
                $model->created_by = $user->name . ' (' . $user->NIK . ')';
                $model->updated_by = $user->name . ' (' . $user->NIK . ')';
            }
        });

        static::created(function ($model) {
            // Kirim notifikasi setelah berhasil dibuat
            if (!empty($model->password)) {
                $model->notify(new \App\Notifications\UserRegisteredEmail($model->password));
            }
        });

        static::updating(function ($model) {
            if (Filament::auth()->user()) {
                $user = Filament::auth()->user();
                $changed = [];

                $plainPassword = $model->password_plain;

                foreach ($model->getDirty() as $key => $value) {
                    $original = $model->getOriginal($key);

                    if ($key === 'password') {
                        if (!filled($plainPassword) || Hash::check($plainPassword, $original)) {
                            continue; // hash baru tapi password sama
                        } else {
                            $changed[] = "$key";
                            continue;
                        }
                    }
                    if (in_array($key, ['remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])) {
                        continue;
                    }

                    $changed[] = "$key: '$original' → '$value'";
                }

                // Jika ada perubahan penting, catat ke updated_by dan keterangan
                if (count($changed) > 0) {
                    $model->updated_by = $user->name . ' (' . $user->NIK . ')';
                    $model->keterangan = 'Change: ' . Str::limit(implode(', ', $changed), 255);
                } else {
                    // Jangan ubah nilai yang ada di DB
                    $model->updated_by = $model->getOriginal('updated_by');
                    $model->keterangan = $model->getOriginal('keterangan');
                }
            }
        });
    }
    /**
     * Mengizinkan semua pengguna mengakses panel Filament
     * Pengecekan lebih detail dilakukan di middleware dan login
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin' && $this->role === 'user') {
            return false;
        }

        return true;
    }

    public string|null $password_plain = null;
}
