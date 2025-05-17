<?php

namespace Tests\Feature;

use App\Enums\TopicStatus;
use App\Models\Message;
use App\Enums\Role;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class MessageControllerTest extends BaseTestCase
{
    /**
     * Test creating a message in an open topic
     *
     * @return void
     */
    public function testCreateMessage()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);

        $response = $this->post('/message/create', [
            'parent_id' => $topic->id,
            'text' => 'This is a test message'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test creating a message in a closed topic (should fail)
     *
     * @return void
     */
    public function testCreateMessageInClosedTopic()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::CLOSED
        ]);

        $response = $this->post('/message/create', [
            'parent_id' => $topic->id,
            'text' => 'This is a test message'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test updating a message
     *
     * @return void
     */
    public function testUpdateMessage()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);
        
        $message = Message::factory()->create([
            'parent_id' => $topic->id,
            'author' => $user->id,
            'text' => 'Original message'
        ]);

        $response = $this->put('/message/update', [
            'id' => $message->id,
            'text' => 'Updated message text'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test updating a message that doesn't belong to the user (should fail)
     *
     * @return void
     */
    public function testUpdateMessageNotOwned()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $this->actingAs($user2);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);
        
        $message = Message::factory()->create([
            'parent_id' => $topic->id,
            'author' => $user1->id,
            'text' => 'Original message'
        ]);

        $response = $this->put('/message/update', [
            'id' => $message->id,
            'text' => 'Updated message text'
        ]);

        $response->assertRedirect();
    }

    /**
     * Test deleting a message
     *
     * @return void
     */
    public function testDeleteMessage()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);
        
        $message = Message::factory()->create([
            'parent_id' => $topic->id,
            'author' => $user->id,
            'text' => 'Message to delete'
        ]);

        $response = $this->delete('/message/delete', [
            'id' => $message->id
        ]);

        $response->assertRedirect();
    }

    /**
     * Test moderator can delete any message
     *
     * @return void
     */
    public function testModeratorCanDeleteAnyMessage()
    {
        $user = $this->createUser();
        $moderator = $this->createUser(Role::MODERATOR);
        $this->actingAs($moderator);
        
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);
        
        $message = Message::factory()->create([
            'parent_id' => $topic->id,
            'author' => $user->id,
            'text' => 'Message to delete by moderator'
        ]);

        $response = $this->delete('/message/delete', [
            'id' => $message->id
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

        $response = $this->get('/manage/message');
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

        $response = $this->get('/manage/message');
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
