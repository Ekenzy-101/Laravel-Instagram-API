<?php

namespace Database\Factories;

use App\Models\ReplyComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReplyComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->uuid,
            'content' => $this->faker->sentence(12)
        ];
    }
}
