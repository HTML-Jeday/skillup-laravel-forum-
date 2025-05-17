<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubcategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(?User $user)
    {
        return true; // Anyone can view subcategories
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Subcategory $subcategory)
    {
        return true; // Anyone can view a subcategory
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subcategory  $subcategory
     * @return mixed
     */
    public function update(User $user, Subcategory $subcategory)
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subcategory  $subcategory
     * @return mixed
     */
    public function delete(User $user, Subcategory $subcategory)
    {
        return $user->role === Role::ADMIN;
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
}
