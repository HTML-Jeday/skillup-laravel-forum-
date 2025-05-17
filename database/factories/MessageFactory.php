<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author' => User::factory(),
            'text' => $this->faker->paragraph,
            'parent_id' => Topic::factory(),
        ];
    }
}
