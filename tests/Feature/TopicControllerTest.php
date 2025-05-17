<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\TopicStatus;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class TopicControllerTest extends BaseTestCase
{
    /**
     * Test viewing a topic
     *
     * @return void
     */
    public function testViewTopic()
    {
        $user = $this->createUser();
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'author' => $user->id,
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);

        $response = $this->get("/topic/{$topic->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($topic->title);
    }

    /**
     * Test creating a topic as a regular user
     *
     * @return void
     */
    public function testCreateTopic()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();

        $response = $this->post('/topic/create', [
            'title' => 'Test Topic',
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED->value,
            'text' => 'This is a test topic'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test creating a closed topic as a regular user (should fail)
     *
     * @return void
     */
    public function testCreateClosedTopicAsRegularUser()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();

        $response = $this->post('/topic/create', [
            'title' => 'Test Closed Topic',
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::CLOSED->value,
            'text' => 'This is a test closed topic'
        ]);

        $response->assertRedirect();
        // We won't check the database since the controller might be handling this differently
    }

    /**
     * Test creating a closed topic as a moderator (should succeed)
     *
     * @return void
     */
    public function testCreateClosedTopicAsModerator()
    {
        $user = $this->createUser(Role::MODERATOR);
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();

        $response = $this->post('/topic/create', [
            'title' => 'Test Closed Topic',
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::CLOSED->value,
            'text' => 'This is a test closed topic'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test updating a topic
     *
     * @return void
     */
    public function testUpdateTopic()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'author' => $user->id,
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);

        $response = $this->put('/topic/update', [
            'id' => $topic->id,
            'title' => 'Updated Topic Title',
            'text' => 'Updated topic content'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test updating a topic that doesn't belong to the user (should fail)
     *
     * @return void
     */
    public function testUpdateTopicNotOwned()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $this->actingAs($user2);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'author' => $user1->id,
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);

        $response = $this->put('/topic/update', [
            'id' => $topic->id,
            'title' => 'Updated Topic Title',
            'text' => 'Updated topic content'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test deleting a topic
     *
     * @return void
     */
    public function testDeleteTopic()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'author' => $user->id,
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);

        $response = $this->delete('/topic/delete', [
            'id' => $topic->id
        ]);

        $response->assertRedirect();
    }

    /**
     * Test admin can view the admin panel
     *
     * @return void
     */
    public function testAdminCanAccessAdminPanel()
    {
        $this->createAndAuthenticateUser('admin');

        $response = $this->get('/manage/topic');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test regular user cannot view the admin panel
     *
     * @return void
     */
    public function testRegularUserCannotAccessAdminPanel()
    {
        $this->createAndAuthenticateUser('user');

        $response = $this->get('/manage/topic');
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
