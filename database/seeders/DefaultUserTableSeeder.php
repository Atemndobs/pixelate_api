<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DefaultUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $atem_lat = 8.503972;
        $atem_long = 51.017243;
        $atemLocation = "ST_GeomFromText('POINT($atem_lat $atem_long)')";

        $default = [
            'id' => 100000,
            'name' => 'Default User',
            'username' => 'not logged in',
            'tagline' => 'Default Profile',
            'email' => 'default@gmail.com',
            'email_verified_at' => now(),
            'formatted_address' => 'New York City',
            'about' => 'Please Log in To Like of react to Posts',
            'password' => '$2y$10$UZjGPBj7DIMdXIHhGz63UeMHchNWlIhDV7qqTgIw19GPcMuVDGKhi', // pass1234
            'remember_token' => Str::random(10),
            'available_to_hire' => true,
            'location' =>  DB::raw($atemLocation),
            'uuid' => $faker->uuid,
            'created_at' => new \DateTime('now'),
            'updated_at' => new \DateTime('now')
        ];
        \DB::table('users')->insert($default);
    }
}
