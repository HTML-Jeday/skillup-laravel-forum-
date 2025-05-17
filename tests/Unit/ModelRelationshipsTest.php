<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Message;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User-Topic relationship
     *
     * @return void
     */
    public function testUserTopicRelationship()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create(['author' => $user->id]);

        $this->assertTrue($user->topics->contains($topic));
        $this->assertEquals($user->id, $topic->user->id);
    }

    /**
     * Test User-Message relationship
     *
     * @return void
     */
    public function testUserMessageRelationship()
    {
        $user = User::factory()->create();
        $message = Message::factory()->create(['author' => $user->id]);

        $this->assertTrue($user->messages->contains($message));
        $this->assertEquals($user->id, $message->user->id);
    }

    /**
     * Test Category-Subcategory relationship
     *
     * @return void
     */
    public function testCategorySubcategoryRelationship()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['parent_id' => $category->id]);

        $this->assertTrue($category->subcategories->contains($subcategory));
        $this->assertEquals($category->id, $subcategory->category->id);
    }

    /**
     * Test Subcategory-Topic relationship
     *
     * @return void
     */
    public function testSubcategoryTopicRelationship()
    {
        $subcategory = Subcategory::factory()->create();
        $topic = Topic::factory()->create(['parent_id' => $subcategory->id]);

        $this->assertTrue($subcategory->topics->contains($topic));
        $this->assertEquals($subcategory->id, $topic->subcategory->id);
    }

    /**
     * Test Topic-Message relationship
     *
     * @return void
     */
    public function testTopicMessageRelationship()
    {
        $topic = Topic::factory()->create();
        $message = Message::factory()->create(['parent_id' => $topic->id]);

        $this->assertTrue($topic->messages->contains($message));
        $this->assertEquals($topic->id, $message->topic->id);
    }
}
