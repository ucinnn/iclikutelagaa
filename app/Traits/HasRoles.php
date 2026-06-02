<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    /**
     * Mengecek apakah pengguna memiliki role tertentu
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        // Menggunakan cache untuk meningkatkan performa
        $cacheKey = 'user_roles_' . $this->id;

        $roles = Cache::remember($cacheKey, 60 * 60, function () {
            return $this->roles()->pluck('name')->toArray();
        });

        return in_array($role, $roles);
    }

    /**
     * Mengecek apakah pengguna memiliki salah satu dari beberapa role
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Mengecek apakah pengguna memiliki semua role yang disebutkan
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Relasi ke tabel roles
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_roles');
    }
}
