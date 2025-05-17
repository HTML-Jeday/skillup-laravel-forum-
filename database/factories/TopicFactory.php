<?php

namespace Database\Factories;

use App\Enums\TopicStatus;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => User::factory(),
            'parent_id' => Subcategory::factory(),
            'text' => $this->faker->sentence(10),
            'status' => TopicStatus::OPENED->value,
        ];
    }

    /**
     * Indicate that the topic is closed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TopicStatus::CLOSED->value,
            ];
        });
    }
}
