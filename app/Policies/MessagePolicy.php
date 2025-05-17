<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Message $message)
    {
        return true; // Anyone can view messages
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true; // Any authenticated user can create messages
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Message $message)
    {
        // Admin can update any message
        if ($user->role === Role::ADMIN) {
            return true;
        }
        
        // Only message author can update their own messages
        return $message->author === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return mixed
     */
    public function delete(User $user, Message $message)
    {
        // Admins can delete any message
        if ($user->role === Role::ADMIN) {
            return true;
        }

        // Moderators can delete messages from regular users
        if ($user->role === Role::MODERATOR) {
            $messageAuthor = $message->user;
            
            // If message author is admin or moderator, moderators can't delete it
            if ($messageAuthor && in_array($messageAuthor->role, [Role::ADMIN, Role::MODERATOR])) {
                return false;
            }
            
            return true;
        }

        // Users can only delete their own messages
        return $user->id === $message->author;
    }

    /**
     * Determine whether the user has admin capabilities.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function admin(User $user)
    {
        return in_array($user->role, [Role::ADMIN, Role::MODERATOR]);
    }
}
