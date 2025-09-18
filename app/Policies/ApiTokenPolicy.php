<?php

namespace App\Policies;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiTokenPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, ApiToken $token)
    {
        return $user->id === $token->user_id;
    }
}
