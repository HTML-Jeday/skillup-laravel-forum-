<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Create and authenticate a user with a specific role
     *
     * @param string|Role $role
     * @return \Illuminate\Testing\TestResponse
     */
    protected function createAndAuthenticateUser($role = Role::USER)
    {
        $roleValue = $role instanceof Role ? $role->value : $role;
        
        $user = User::factory()->create([
            'role' => $roleValue,
//            'email_verified_at' => now(),
        ]);

        return $this->actingAs($user);
    }

    /**
     * Create a user with a specific role
     *
     * @param string|Role $role
     * @return User
     */
    protected function createUser($role = Role::USER)
    {
        $roleValue = $role instanceof Role ? $role->value : $role;
        
        return User::factory()->create([
            'role' => $roleValue,
//            'email_verified_at' => now(),
        ]);
    }
}
