<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true; // Anyone can view the users list
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return true; // Anyone can view user profiles
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role === Role::ADMIN; // Only admins can create users
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->id === $model->id || $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if ($user->role === Role::ADMIN) {
            return $model->role !== Role::ADMIN || $user->id === $model->id;
        }

        if ($user->role === Role::MODERATOR) {
            return $model->role === Role::USER || $user->id === $model->id;
        }

        return $user->id === $model->id;
    }

    /**
     * Determine whether the user has admin capabilities.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function admin(User $user)
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can verify other users.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function verify(User $user)
    {
        return $user->role === Role::ADMIN || $user->role === Role::MODERATOR;
    }
}
