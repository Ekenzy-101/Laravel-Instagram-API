<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->uuid,
            'caption' => $this->faker->sentence(12),
            'image_urls' => ['image_1.jpg', 'image_2.jpg', 'image_3.jpg'],
            'keys' => ['image_1.jpg', 'image_2.jpg', 'image_3.jpg'],
        ];
    }
}
