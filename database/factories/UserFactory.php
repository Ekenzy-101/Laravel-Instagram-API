<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->uuid,
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->userName,
            'bio' => $this->faker->sentence(12),
            'gender' => 'Male',
            'website' => $this->faker->url,
            'phone_no' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now()->toDateTimeString(),
            'password' => '$2y$10$NRUFGHJ1SjJ95dibP1aYV.5ZMPlDCrkJQF9sxzPXtyT5ZZqLyXtsu',
        ];
    }
}
