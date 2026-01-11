<?php

namespace App\Policies;

use App\Models\LostFound;
use App\Models\User;

class LostFoundPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, LostFound $lostFound): bool
    {
        return $user->id === $lostFound->reported_by;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, LostFound $lostFound): bool
    {
        return $user->id === $lostFound->reported_by;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, LostFound $lostFound): bool
    {
        return $user->id === $lostFound->reported_by;
    }
}