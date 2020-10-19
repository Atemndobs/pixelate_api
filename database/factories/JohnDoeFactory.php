<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    $lat = $faker->randomFloat(6, 48, 54);
    $long = $faker->randomFloat(6, 6, 15);
    $location = "ST_GeomFromText('POINT($lat $long)')";
    return [
        'name' => 'John Doe',
        'username' => 'johnny',
        'tagline' => $faker->jobTitle,
        'email' => 'johnny@gmail.com',
        'email_verified_at' => now(),
        'formatted_address' => $faker->address,
        'about' => $faker->realText(40),
        'password' => '$2y$10$UZjGPBj7DIMdXIHhGz63UeMHchNWlIhDV7qqTgIw19GPcMuVDGKhi', // pass1234
        'remember_token' => Str::random(10),
        'available_to_hire' => true,
        'location' =>  DB::raw($location),
    ];
});
