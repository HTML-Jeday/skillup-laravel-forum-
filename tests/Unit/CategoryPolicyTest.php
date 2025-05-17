<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    private CategoryPolicy $policy;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new CategoryPolicy();
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
        $category = Category::factory()->create();
        $this->assertTrue($this->policy->view($user, $category));
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
     * Test update method for admin
     *
     * @return void
     */
    public function testUpdateForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $this->assertTrue($this->policy->update($admin, $category));
    }

    /**
     * Test update method for moderator
     *
     * @return void
     */
    public function testUpdateForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $category = Category::factory()->create();
        $this->assertFalse($this->policy->update($moderator, $category));
    }

    /**
     * Test update method for regular user
     *
     * @return void
     */
    public function testUpdateForRegularUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $this->assertFalse($this->policy->update($user, $category));
    }

    /**
     * Test delete method for admin
     *
     * @return void
     */
    public function testDeleteForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $this->assertTrue($this->policy->delete($admin, $category));
    }

    /**
     * Test delete method for moderator
     *
     * @return void
     */
    public function testDeleteForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $category = Category::factory()->create();
        $this->assertFalse($this->policy->delete($moderator, $category));
    }

    /**
     * Test delete method for regular user
     *
     * @return void
     */
    public function testDeleteForRegularUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $this->assertFalse($this->policy->delete($user, $category));
    }
}
