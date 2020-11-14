<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
               //'id' => $this->faker->numberBetween(10,50),
            'user_id' => $this->faker->unique()->numberBetween(1,10),
            'likeable_id' => 1,
            'likeable_type' => 'App\\Models\\Design',
        ];
    }
}
