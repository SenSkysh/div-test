<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        if(!isset($user)) {
            $user = auth('sanctum')->user();
        }
        // return true;
        return isset($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Request $request): bool
    {
        if(!isset($user)) {
            $user = auth('sanctum')->user();
        }
        // return true;
        return isset($user);
    }

}
