<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Design;
use Faker\Generator as Faker;

$factory->define(Design::class, function (Faker $faker) {
    return [
        "id" => $faker->randomNumber(2, true),
        'user_id' => 1,
        "title" => $faker->name,
        "description" => $faker->text(20),
        "slug" => $faker->slug,
        "disk" => 'public',
        'image'=> $faker->image(),
    ];
});
