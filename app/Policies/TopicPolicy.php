<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
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
    public function view(?User $user, Topic $topic)
    {
        return true; // Anyone can view topics
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true; // Any authenticated user can create topics
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function update(User $user, Topic $topic)
    {
        // Topic author can update their own topics
        if ($user->id === $topic->author) {
            return true;
        }

        // Admins and moderators can update any topic
        if (in_array($user->role, [Role::ADMIN, Role::MODERATOR])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Topic  $topic
     * @return mixed
     */
    public function delete(User $user, Topic $topic)
    {
        // Admins can delete any topic
        if ($user->role === Role::ADMIN) {
            return true;
        }

        // Moderators can delete topics from regular users
        if ($user->role === Role::MODERATOR) {
            $topicAuthor = $topic->user;
            
            // If topic author is admin or moderator, moderators can't delete it
            if ($topicAuthor && in_array($topicAuthor->role, [Role::ADMIN, Role::MODERATOR])) {
                return false;
            }
            
            return true;
        }

        // Users can only delete their own topics
        return $user->id === $topic->author;
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
