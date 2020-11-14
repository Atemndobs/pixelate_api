<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //   'id' => $faker->unique()->numberBetween(1, 10),
            'name' =>$this->faker->jobTitle,
            'slug' => $this->faker->slug,
            'owner_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}
