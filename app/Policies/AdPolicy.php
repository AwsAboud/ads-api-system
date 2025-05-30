<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdPolicy
{
    /**
     * Determine if the authenticated user can update the ad.
     */
    public function update(User $user, Ad $ad): Response
    {
        return $user->isAdmin() || $user->id === $ad->user_id
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine if the authenticated user can delete the ad.
     */
    public function delete(User $user, Ad $ad): Response
    {
        return $user->isAdmin() || $user->id === $ad->user_id
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine if the authenticated user can change status of the ad.
     */
    public function changeStatus(User $user, Ad $ad): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

}
