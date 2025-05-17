<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class SubcategoryControllerTest extends BaseTestCase
{
    /**
     * Test viewing a subcategory
     *
     * @return void
     */
    public function testViewSubcategory()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'title' => 'Test Subcategory',
            'parent_id' => $category->id
        ]);

        $response = $this->get("/subcategory/{$subcategory->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Test Subcategory');
    }

    /**
     * Test viewing a non-existent subcategory
     *
     * @return void
     */
    public function testViewNonExistentSubcategory()
    {
        $response = $this->get('/subcategory/9999'); // Non-existent ID
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Test admin can view subcategory admin panel
     *
     * @return void
     */
    public function testAdminCanViewSubcategoryAdmin()
    {
        $this->createAndAuthenticateUser(Role::ADMIN);
        
        $response = $this->get('/admin/subcategory');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test regular user cannot access subcategory admin panel
     *
     * @return void
     */
    public function testRegularUserCannotAccessSubcategoryAdmin()
    {
        $this->createAndAuthenticateUser(Role::USER);

        $response = $this->get('/admin/subcategory');
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test creating a subcategory as admin
     *
     * @return void
     */
    public function testAdminCanCreateSubcategory()
    {
        $this->createAndAuthenticateUser(Role::ADMIN);
        $category = Category::factory()->create();

        $response = $this->post('/admin/subcategory/create', [
            'title' => 'New Test Subcategory',
            'parent_id' => $category->id
        ]);

        $response->assertRedirect();
    }

    /**
     * Test creating a subcategory with duplicate title in same category (should fail)
     *
     * @return void
     */
    public function testCannotCreateDuplicateSubcategory()
    {
        $this->createAndAuthenticateUser(Role::ADMIN);
        $category = Category::factory()->create();
        Subcategory::factory()->create([
            'title' => 'Existing Subcategory',
            'parent_id' => $category->id
        ]);

        $response = $this->post('/admin/subcategory/create', [
            'title' => 'Existing Subcategory',
            'parent_id' => $category->id
        ]);

        $response->assertRedirect();
    }

    /**
     * Test updating a subcategory as admin
     *
     * @return void
     */
    public function testAdminCanUpdateSubcategory()
    {
        $this->createAndAuthenticateUser(Role::ADMIN);
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'title' => 'Original Subcategory Title',
            'parent_id' => $category->id
        ]);

        $response = $this->put('/admin/subcategory/update', [
            'id' => $subcategory->id,
            'title' => 'Updated Subcategory Title'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test deleting a subcategory as admin
     *
     * @return void
     */
    public function testAdminCanDeleteSubcategory()
    {
        $this->createAndAuthenticateUser(Role::ADMIN);
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'title' => 'Subcategory to Delete',
            'parent_id' => $category->id
        ]);

        $response = $this->delete('/admin/subcategory/delete', [
            'id' => $subcategory->id
        ]);

        $response->assertRedirect();
    }

    /**
     * Test deleting a subcategory with topics (should fail)
     *
     * @return void
     */
    public function testCannotDeleteSubcategoryWithTopics()
    {
        $this->createAndAuthenticateUser(Role::ADMIN);
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'title' => 'Subcategory with Topics',
            'parent_id' => $category->id
        ]);
        
        Topic::factory()->create([
            'parent_id' => $subcategory->id
        ]);

        $response = $this->delete('/admin/subcategory/delete', [
            'id' => $subcategory->id
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('subcategories', [
            'id' => $subcategory->id
        ]);
    }
}
