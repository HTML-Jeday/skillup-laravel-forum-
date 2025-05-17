<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create main categories
        $categories = [
            'General Discussion',
            'Programming',
            'Web Development',
            'Mobile Development',
            'Game Development',
            'DevOps',
            'Career Advice',
            'Off-Topic'
        ];

        foreach ($categories as $categoryTitle) {
            Category::create([
                'title' => $categoryTitle
            ]);
        }

        // Create a few more random categories
        Category::factory()->count(3)->create();
    }
}
