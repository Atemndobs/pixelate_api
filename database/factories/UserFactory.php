<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $lat = $faker->randomFloat(6, 48, 54);
        $long = $faker->randomFloat(6, 6, 15);
        $location = "ST_GeomFromText('POINT($lat $long)')";

        $user = [
            'name' => $faker->unique()->firstName. " ". $faker->unique()->lastName,
            'username' => strtolower($faker->unique()->firstName.$faker->randomNumber(3)),
            'tagline' => $faker->jobTitle,
            'email' => strtolower($faker->unique()->firstName.$faker->randomNumber(3).'@email.com'),
            'email_verified_at' => now(),
            'formatted_address' => $faker->address,
            'about' => $faker->realText(40),
            'password' => '$2y$10$UZjGPBj7DIMdXIHhGz63UeMHchNWlIhDV7qqTgIw19GPcMuVDGKhi', // pass1234
            'remember_token' => Str::random(10),
            'available_to_hire' => true,
            'location' =>  DB::raw($location),
            'uuid' => $faker->uuid
        ];

        return $user;
    }
}
