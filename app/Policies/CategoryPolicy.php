<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
  
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
         return $user->role == 'admin'
            ? Response::allow()
            : Response::denyWithStatus(403);
    }        

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

}
