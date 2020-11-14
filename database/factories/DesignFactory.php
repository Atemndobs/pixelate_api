<?php

namespace Database\Factories;

use App\Models\Design;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Design::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        return [
            //"id" => $faker->unique()->numberBetween(1,10),
            // 'user_id' => factory(\App\Models\User::class)->create(),
            'user_id' => User::factory(),
            "title" => $faker->name,
            "description" => $faker->text(20),
            "slug" => $faker->slug,
            "disk" => 'public',
            'image'=> $faker->image(),
        ];
    }
}
