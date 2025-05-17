<?php

namespace Tests\Unit;

use App\Models\Subcategory;
use App\Models\User;
use App\Policies\SubcategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubcategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    private SubcategoryPolicy $policy;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SubcategoryPolicy();
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
        $subcategory = Subcategory::factory()->create();
        $this->assertTrue($this->policy->view($user, $subcategory));
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
        $subcategory = Subcategory::factory()->create();
        $this->assertTrue($this->policy->update($admin, $subcategory));
    }

    /**
     * Test update method for moderator
     *
     * @return void
     */
    public function testUpdateForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $subcategory = Subcategory::factory()->create();
        $this->assertFalse($this->policy->update($moderator, $subcategory));
    }

    /**
     * Test update method for regular user
     *
     * @return void
     */
    public function testUpdateForRegularUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        $subcategory = Subcategory::factory()->create();
        $this->assertFalse($this->policy->update($user, $subcategory));
    }

    /**
     * Test delete method for admin
     *
     * @return void
     */
    public function testDeleteForAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subcategory = Subcategory::factory()->create();
        $this->assertTrue($this->policy->delete($admin, $subcategory));
    }

    /**
     * Test delete method for moderator
     *
     * @return void
     */
    public function testDeleteForModerator()
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $subcategory = Subcategory::factory()->create();
        $this->assertFalse($this->policy->delete($moderator, $subcategory));
    }

    /**
     * Test delete method for regular user
     *
     * @return void
     */
    public function testDeleteForRegularUser()
    {
        $user = User::factory()->create(['role' => 'user']);
        $subcategory = Subcategory::factory()->create();
        $this->assertFalse($this->policy->delete($user, $subcategory));
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
