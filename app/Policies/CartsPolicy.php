<?php

namespace App\Policies;

use App\Models\Carts;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->id
            ? Response::allow()
            : Response::deny('you dont have permission to access the cart!');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Carts $cart): Response
    {

        return $user->id === $cart->user_id
            ? Response::allow()
            : Response::deny('Unauthorized Action!');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->id
            ? Response::allow()
            : Response::deny('Unauthorized Action!');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Carts $carts): Response
    {
        return $user->id === $carts->user_id
            ? Response::allow()
            : Response::deny('Unauthorized Action!');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Carts $carts): Response
    {
        return $user->id === $carts->user_id
           ? Response::allow()
           : Response::deny('Unauthorized Action!');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Carts $carts): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Carts $carts): bool
    {
        return false;
    }
}
