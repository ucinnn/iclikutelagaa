<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Tentukan apakah user bisa melihat daftar user (hanya admin).
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'superadmin';
    }

    /**
     * Tentukan apakah user bisa melihat data user tertentu.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->role === 'superadmin' || $user->id === $model->id;
    }


    /**
     * Tentukan apakah user bisa mengupdate data user tertentu.
     */
    public function update(User $user, User $model): bool
    {
        // Tidak ada yang bisa mengedit superadmin kecuali superadmin sendiri
        if ($model->role === 'superadmin' && $user->role !== 'superadmin') {
            return false;
        }

        // admin tidak boleh mengedit admin lain
        if ($model->role === 'admin' && $user->role === 'admin' && $user->id !== $model->id) {
            return false;
        }

        // superadmin bisa update siapa saja, termasuk sesama superadmin
        if ($user->role === 'superadmin') {
            return true;
        }

        // admin bisa update semua kecuali superadmin
        if ($user->role === 'admin') {
            return true;
        }

        // User biasa hanya boleh update dirinya sendiri
        return $user->id === $model->id;
    }


    /**
     * Tentukan apakah user bisa mengupdate email.
     */
    public function updateEmail(User $user, User $model): bool
    {
        // Hanya superadmin bisa ubah email
        return $user->role === 'superadmin';
    }

    /**
     * Tentukan apakah user bisa mengupdate NIK.
     */
    public function updateNik(User $user): bool
    {
        // Hanya admin bisa ubah NIK
        return $user->role === 'superadmin';
    }

    /**
     * Tentukan apakah user bisa mengupdate role.
     */
    public function updateRole(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->role === 'superadmin';
    }

    /**
     * Tentukan apakah user bisa menghapus user (hanya admin).
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }
}
