<?php

namespace Database\Factories\User;

use App\Models\User\UserProfile;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'nip' => $this->faker->unique()->numerify('NIP######'),
            'avatar' => $this->faker->imageUrl(200, 200, 'people', true),
            'user_id' => User::factory(), // Creates a related user
        ];
    }
}
