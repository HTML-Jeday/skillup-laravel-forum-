<?php

namespace Database\Seeders;

use App\Enums\TopicStatus;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all subcategories and users
        $subcategories = Subcategory::all();
        $users = User::all();
        
        // Sample topic titles for different categories
        $sampleTopics = [
            'Introductions' => [
                'Hello everyone, I\'m new here!',
                'Introducing myself to the community',
                'New member from Canada'
            ],
            'Python' => [
                'Best practices for Python virtual environments',
                'How to optimize Pandas performance',
                'Django vs Flask - which one to choose?'
            ],
            'JavaScript' => [
                'Understanding closures in JavaScript',
                'React vs Vue - pros and cons',
                'How to debug memory leaks in Node.js'
            ],
            'PHP' => [
                'Laravel 8 new features discussion',
                'PHP 8 attributes and how to use them',
                'Best practices for Composer package management'
            ],
            'Frontend' => [
                'CSS Grid vs Flexbox',
                'State management in modern web apps',
                'Progressive Web Apps - experiences and tips'
            ]
        ];
        
        // Create topics for each subcategory
        foreach ($subcategories as $subcategory) {
            // If we have sample topics for this subcategory, use them
            if (isset($sampleTopics[$subcategory->title])) {
                foreach ($sampleTopics[$subcategory->title] as $topicTitle) {
                    $randomUser = $users->random();
                    Topic::create([
                        'title' => $topicTitle,
                        'author' => $randomUser->id,
                        'parent_id' => $subcategory->id,
                        'text' => 'This is the initial post for the topic: ' . $topicTitle,
                        'status' => rand(0, 5) > 4 ? TopicStatus::CLOSED->value : TopicStatus::OPENED->value,
                    ]);
                }
            }
            
            // Create some random topics for each subcategory
            $count = rand(3, 8);
            Topic::factory()->count($count)->create([
                'parent_id' => $subcategory->id,
                'author' => $users->random()->id,
            ]);
            
            // Create a few closed topics
            $closedCount = rand(1, 2);
            Topic::factory()->closed()->count($closedCount)->create([
                'parent_id' => $subcategory->id,
                'author' => $users->random()->id,
            ]);
        }
    }
}
