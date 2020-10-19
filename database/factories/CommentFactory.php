<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
      //  'id' => $faker->randomNumber(2, true),
       // 'user_id' => factory(\App\Models\User::class)->create()?factory(\App\Models\User::class)->create():factory(\App\Models\User::class)->create(),
        'user_id' => $faker->numberBetween(1,10),
        'body' => $faker->realText(50),
        'commentable_type' => "App\Models\Design",
        'commentable_id' => $faker->numberBetween(1,10),
    ];
});
