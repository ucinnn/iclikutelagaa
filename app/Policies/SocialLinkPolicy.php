<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SocialLinkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SocialLink $SocialLink): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SocialLink $SocialLink): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SocialLink $SocialLink): bool
    {
        return false;
    }
}