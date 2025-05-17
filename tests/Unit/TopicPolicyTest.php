<?php

namespace Tests\Unit;

use App\Models\Topic;
use App\Models\User;
use App\Policies\TopicPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicPolicyTest extends TestCase
{
    use RefreshDatabase;

    private TopicPolicy $policy;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TopicPolicy();
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
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $this->assertTrue($this->policy->view($user, $topic));
    }

    /**
     * Test create method
     *
     * @return void
     */
    public function testCreate()
    {
        $user = User::factory()->create();
        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test update method for topic owner
     *
     * @return void
     */
    public function testUpdateForOwner()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);
        $this->assertTrue($this->policy->update($user, $topic));
    }

    /**
     * Test update method for non-owner regular user
     *
     * @return void
     */
    public function testUpdateForNonOwnerRegularUser()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user1->id]);
        $this->assertFalse($this->policy->update($user2, $topic));
    }

    /**
     * Test update method for admin
     *
     * @return void
     */
    public function testUpdateForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);
        $this->assertTrue($this->policy->update($admin, $topic));
    }

    /**
     * Test update method for moderator
     *
     * @return void
     */
    public function testUpdateForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);
        $this->assertTrue($this->policy->update($moderator, $topic));
    }

    /**
     * Test delete method for topic owner
     *
     * @return void
     */
    public function testDeleteForOwner()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);
        $this->assertTrue($this->policy->delete($user, $topic));
    }

    /**
     * Test delete method for non-owner regular user
     *
     * @return void
     */
    public function testDeleteForNonOwnerRegularUser()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user1->id]);
        $this->assertFalse($this->policy->delete($user2, $topic));
    }

    /**
     * Test delete method for admin
     *
     * @return void
     */
    public function testDeleteForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);
        $this->assertTrue($this->policy->delete($admin, $topic));
    }

    /**
     * Test delete method for moderator
     *
     * @return void
     */
    public function testDeleteForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);
        $this->assertTrue($this->policy->delete($moderator, $topic));
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
        $this->assertTrue($this->policy->admin($moderator));
    }

    /**
     * Test admin method for regular user
     *
     * @return void
     */
    public function testAdminForRegularUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->admin($user));
    }
}
