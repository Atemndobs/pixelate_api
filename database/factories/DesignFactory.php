<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Design;
use Faker\Generator as Faker;



    $factory->define(Design::class, function (Faker $faker) {


        return [
          //"id" => $faker->unique()->numberBetween(1,10),
           // 'user_id' => factory(\App\Models\User::class)->create(),
            'user_id' => $faker->numberBetween(1,20),
            "title" => $faker->name,
            "description" => $faker->text(20),
            "slug" => $faker->slug,
            "disk" => 'public',
            'image'=> $faker->image(),
        ];
    });


