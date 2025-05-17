<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserControllerTest extends BaseTestCase
{
    /**
     * Test viewing user list
     *
     * @return void
     */
    public function testViewUserList()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->get('/user/profile');
        $response->assertStatus(ResponseAlias::HTTP_OK);
        $response->assertSee($user->name);
    }

    /**
     * Test updating user profile
     *
     * @return void
     */
    public function testUpdateUserProfile()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $this->put('/user/update', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'FirstName' => 'Updated First',
            'LastName' => 'Updated Last',
            'gender' => Gender::MALE->value,
            'password' => 'newpassword123'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'FirstName' => 'Updated First',
            'LastName' => 'Updated Last',
            'gender' => Gender::MALE->value
        ]);

        // Check that password was hashed properly
        $updatedUser = User::find($user->id);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
    }

    /**
     * Test updating another user's profile (should fail)
     *
     * @return void
     */
    public function testCannotUpdateAnotherUserProfile()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $this->actingAs($user1);

        $response = $this->put('/user/update', [
            'id' => $user2->id,
            'name' => 'Hacked Name'
        ]);

        $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
    }

    /**
     * Test admin can update any user's profile
     *
     * @return void
     */
    public function testAdminCanUpdateAnyUserProfile()
    {
        $admin = $this->createUser(Role::ADMIN);
        $user = $this->createUser(Role::USER);
        $this->actingAs($admin);

        $this->put('/user/update', [
            'id' => $user->id,
            'name' => 'Admin Updated Name'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Admin Updated Name'
        ]);
    }

    /**
     * Test deleting own user account
     *
     * @return void
     */
    public function testDeleteOwnAccount()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->delete('/user/delete', [
            'id' => $user->id
        ]);

        // Follow the redirect and check for success message
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * Test moderator can delete regular user account
     *
     * @return void
     */
    public function testModeratorCanDeleteRegularUser()
    {
        $moderator = $this->createUser(Role::MODERATOR);
        $user = $this->createUser(Role::USER);
        $this->actingAs($moderator);

        $response = $this->delete('/user/delete', [
            'id' => $user->id
        ]);

        // Follow the redirect and check for success message
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * Test moderator cannot delete admin account
     *
     * @return void
     */
    public function testModeratorCannotDeleteAdmin()
    {
        $moderator = $this->createUser(Role::MODERATOR);
        $admin = $this->createUser(Role::ADMIN);
        $this->actingAs($moderator);

        $response = $this->delete('/user/delete', [
            'id' => $admin->id
        ]);

        $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
    }

    /**
     * Test verification email sending
     *
     * @return void
     */
//    public function testVerificationEmailSending()
//    {
//        $admin = $this->createUser('admin');
//        $user = $this->createUser();
//        $this->actingAs($admin);
//
//        // Create verification record
//        Verification::factory()->create([
//            'user_id' => $user->id,
//            'hash' => 'test_verification_hash'
//        ]);
//
//        $response = $this->post('/verification', [
//            'user_id' => $user->id
//        ]);
//
//        $response->assertSessionHas('success');
//    }
}
