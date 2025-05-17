<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class CategoryControllerTest extends BaseTestCase
{
    /**
     * Test viewing categories in admin panel
     *
     * @return void
     */
    public function testAdminCanViewCategories()
    {
        $this->createAndAuthenticateUser('admin');
        Category::factory()->create(['title' => 'Test Category']);

        $response = $this->get('/admin/category');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Test Category');
    }

    /**
     * Test regular user cannot access category admin panel
     *
     * @return void
     */
    public function testRegularUserCannotAccessCategoryAdmin()
    {
        $this->createAndAuthenticateUser('user');

        $response = $this->get('/admin/category');
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test creating a category as admin
     *
     * @return void
     */
    public function testAdminCanCreateCategory()
    {
        $this->createAndAuthenticateUser('admin');

        $response = $this->post('/admin/category/create', [
            'title' => 'New Test Category'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test creating a category with duplicate title (should fail)
     *
     * @return void
     */
    public function testCannotCreateDuplicateCategory()
    {
        $this->createAndAuthenticateUser('admin');
        Category::factory()->create(['title' => 'Existing Category']);

        $response = $this->post('/admin/category/create', [
            'title' => 'Existing Category'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * Test updating a category as admin
     *
     * @return void
     */
    public function testAdminCanUpdateCategory()
    {
        $this->createAndAuthenticateUser('admin');
        $category = Category::factory()->create(['title' => 'Original Title']);

        $response = $this->put('/admin/category/update', [
            'id' => $category->id,
            'title' => 'Updated Title'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test updating a category as moderator (should fail)
     *
     * @return void
     */
    public function testModeratorCannotUpdateCategory()
    {
        $this->createAndAuthenticateUser('moderator');
        $category = Category::factory()->create(['title' => 'Original Title']);

        $response = $this->put('/admin/category/update', [
            'id' => $category->id,
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test deleting a category as admin
     *
     * @return void
     */
    public function testAdminCanDeleteCategory()
    {
        $this->createAndAuthenticateUser('admin');
        $category = Category::factory()->create(['title' => 'Category to Delete']);

        $response = $this->delete('/admin/category/delete', [
            'id' => $category->id
        ]);

        $response->assertRedirect();
    }

    /**
     * Test deleting a non-existent category (should fail)
     *
     * @return void
     */
    public function testCannotDeleteNonExistentCategory()
    {
        $this->createAndAuthenticateUser('admin');

        $response = $this->delete('/admin/category/delete', [
            'id' => 9999 // Non-existent ID
        ]);

        $response->assertRedirect();
    }
}
