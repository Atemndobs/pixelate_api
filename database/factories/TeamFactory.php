<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
     //   'id' => $faker->unique()->numberBetween(1, 10),
        'name' =>$faker->unique()->firstName,
        'slug' => $faker->slug,
        'owner_id' => $faker->unique()->numberBetween(1, 10)
    ];
});
