<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(User::class, function (Faker $faker) {

    $lat = $faker->randomFloat(6, 48, 54);
    $long = $faker->randomFloat(6, 6, 15);
    $location = "ST_GeomFromText('POINT($lat $long)')";
    return [
        'name' => $faker->unique()->firstName. " ". $faker->unique()->lastName,
        'username' => strtolower($faker->unique()->firstName),
        'tagline' => $faker->jobTitle,
        'email' => strtolower($faker->unique()->firstName.'@email.com'),
        'email_verified_at' => now(),
        'formatted_address' => $faker->address,
        'about' => $faker->realText(40),
        'password' => '$2y$10$UZjGPBj7DIMdXIHhGz63UeMHchNWlIhDV7qqTgIw19GPcMuVDGKhi', // pass1234
        'remember_token' => Str::random(10),
        'available_to_hire' => true,
        'location' =>  DB::raw($location),
    ];
});
