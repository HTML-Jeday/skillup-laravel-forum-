<?php

namespace Tests\Unit;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private UserPolicy $policy;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new UserPolicy();
    }

    /**
     * Test viewAny method
     *
     * @return void
     */
    public function testViewAny()
    {
        $user = User::factory()->create();
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->assertTrue($this->policy->view($user1, $user2));
    }

    /**
     * Test create method for admin
     *
     * @return void
     */
    public function testCreateForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->create($admin));
    }

    /**
     * Test create method for moderator
     *
     * @return void
     */
    public function testCreateForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $this->assertFalse($this->policy->create($moderator));
    }

    /**
     * Test create method for regular user
     *
     * @return void
     */
    public function testCreateForRegularUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test update method for own account
     *
     * @return void
     */
    public function testUpdateOwnAccount()
    {
        $user = User::factory()->create();
        $this->assertTrue($this->policy->update($user, $user));
    }

    /**
     * Test update method for admin updating another user
     *
     * @return void
     */
    public function testUpdateForAdminUpdatingOtherUser()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $this->assertTrue($this->policy->update($admin, $user));
    }

    /**
     * Test update method for moderator updating another user
     *
     * @return void
     */
    public function testUpdateForModeratorUpdatingOtherUser()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $user = User::factory()->create();
        $this->assertFalse($this->policy->update($moderator, $user));
    }

    /**
     * Test update method for regular user updating another user
     *
     * @return void
     */
    public function testUpdateForRegularUserUpdatingOtherUser()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->update($user1, $user2));
    }

    /**
     * Test delete method for own account
     *
     * @return void
     */
    public function testDeleteOwnAccount()
    {
        $user = User::factory()->create();
        $this->assertTrue($this->policy->delete($user, $user));
    }

    /**
     * Test delete method for admin deleting a regular user
     *
     * @return void
     */
    public function testDeleteForAdminDeletingRegularUser()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $this->assertTrue($this->policy->delete($admin, $user));
    }

    /**
     * Test delete method for admin deleting another admin
     *
     * @return void
     */
    public function testDeleteForAdminDeletingAnotherAdmin()
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);
        $this->assertFalse($this->policy->delete($admin1, $admin2));
    }

    /**
     * Test delete method for moderator deleting a regular user
     *
     * @return void
     */
    public function testDeleteForModeratorDeletingRegularUser()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $user = User::factory()->create(['role' => 'user']);
        $this->assertTrue($this->policy->delete($moderator, $user));
    }

    /**
     * Test delete method for moderator deleting an admin
     *
     * @return void
     */
    public function testDeleteForModeratorDeletingAdmin()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertFalse($this->policy->delete($moderator, $admin));
    }

    /**
     * Test delete method for moderator deleting another moderator
     *
     * @return void
     */
    public function testDeleteForModeratorDeletingAnotherModerator()
    {
        $moderator1 = User::factory()->create(['role' => 'moderator']);
        $moderator2 = User::factory()->create(['role' => 'moderator']);
        $this->assertFalse($this->policy->delete($moderator1, $moderator2));
    }

    /**
     * Test admin method for admin
     *
     * @return void
     */
    public function testAdminForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->admin($admin));
    }

    /**
     * Test admin method for moderator
     *
     * @return void
     */
    public function testAdminForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $this->assertFalse($this->policy->admin($moderator));
    }

    /**
     * Test verify method for admin
     *
     * @return void
     */
    public function testVerifyForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $this->assertTrue($this->policy->verify($admin, $user));
    }

    /**
     * Test verify method for moderator
     *
     * @return void
     */
    public function testVerifyForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $user = User::factory()->create();
        $this->assertTrue($this->policy->verify($moderator, $user));
    }

    /**
     * Test verify method for regular user
     *
     * @return void
     */
    public function testVerifyForRegularUser()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create();
        $this->assertFalse($this->policy->verify($user1, $user2));
    }
}
