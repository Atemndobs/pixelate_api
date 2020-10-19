<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Like;
use Faker\Generator as Faker;

$factory->define(Like::class, function (Faker $faker) {
    for ($i= 1; $i <= 20; $i++) {
        return [
         //   'id' => $faker->numberBetween(1,20),
            'user_id' => factory(\App\Models\User::class)->create(),
            'likeable_id' => 1,
            'likeable_type' => 'App\\Models\\Design',
        ];
    }

});
