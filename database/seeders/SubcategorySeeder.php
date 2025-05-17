<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all categories
        $categories = Category::all();
        
        // Define subcategories for specific categories
        $subcategoriesMap = [
            'General Discussion' => [
                'Introductions',
                'Announcements',
                'Feedback'
            ],
            'Programming' => [
                'Python',
                'JavaScript',
                'PHP',
                'Java',
                'C#',
                'Go',
                'Rust'
            ],
            'Web Development' => [
                'Frontend',
                'Backend',
                'Frameworks',
                'CSS',
                'HTML'
            ],
            'Mobile Development' => [
                'Android',
                'iOS',
                'React Native',
                'Flutter'
            ]
        ];
        
        // Create subcategories for specific categories
        foreach ($categories as $category) {
            if (isset($subcategoriesMap[$category->title])) {
                foreach ($subcategoriesMap[$category->title] as $subcategoryTitle) {
                    Subcategory::create([
                        'title' => $subcategoryTitle,
                        'parent_id' => $category->id
                    ]);
                }
            } else {
                // For categories without specific subcategories, create 2-3 random ones
                $count = rand(2, 3);
                Subcategory::factory()->count($count)->create([
                    'parent_id' => $category->id
                ]);
            }
        }
    }
}
