<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $loggedInUser, User $user)
    {
        return $loggedInUser->id === $user->id;
    }

    public function delete(User $loggedInUser, User $user)
    {
        return $loggedInUser->id === $user->id;
    }
}
