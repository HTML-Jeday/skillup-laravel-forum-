<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Message;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\MessagePolicy;
use App\Policies\SubcategoryPolicy;
use App\Policies\TopicPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Subcategory::class => SubcategoryPolicy::class,
        Topic::class => TopicPolicy::class,
        Message::class => MessagePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define general gates for common permissions
        Gate::define('manage-categories', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-subcategories', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-topics', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });

        Gate::define('manage-messages', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });

        Gate::define('access-admin-panel', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
        
        Gate::define('manage-users', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
    }
}
