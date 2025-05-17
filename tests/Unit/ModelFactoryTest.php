<?php

namespace Tests\Unit;

use App\Enums\TopicStatus;
use App\Models\Category;
use App\Models\Message;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User factory
     *
     * @return void
     */
    public function testUserFactory()
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->id);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Test Category factory
     *
     * @return void
     */
    public function testCategoryFactory()
    {
        $category = Category::factory()->create();
        
        $this->assertNotNull($category->id);
        $this->assertNotNull($category->title);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /**
     * Test Subcategory factory
     *
     * @return void
     */
    public function testSubcategoryFactory()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'parent_id' => $category->id
        ]);
        
        $this->assertNotNull($subcategory->id);
        $this->assertNotNull($subcategory->title);
        $this->assertEquals($category->id, $subcategory->parent_id);
        $this->assertDatabaseHas('subcategories', ['id' => $subcategory->id]);
    }

    /**
     * Test Topic factory
     *
     * @return void
     */
    public function testTopicFactory()
    {
        $user = User::factory()->create();
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create([
            'author' => $user->id,
            'parent_id' => $subcategory->id,
            'status' => TopicStatus::OPENED
        ]);
        
        $this->assertNotNull($topic->id);
        $this->assertNotNull($topic->title);
        $this->assertNotNull($topic->text);
        $this->assertEquals($user->id, $topic->author);
        $this->assertEquals($subcategory->id, $topic->parent_id);
        $this->assertEquals(TopicStatus::OPENED, $topic->status);
        $this->assertDatabaseHas('topics', ['id' => $topic->id]);
    }

    /**
     * Test Message factory
     *
     * @return void
     */
    public function testMessageFactory()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $message = Message::factory()->create([
            'author' => $user->id,
            'parent_id' => $topic->id
        ]);
        
        $this->assertNotNull($message->id);
        $this->assertNotNull($message->text);
        $this->assertEquals($user->id, $message->author);
        $this->assertEquals($topic->id, $message->parent_id);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }
}
