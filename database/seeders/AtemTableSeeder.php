<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AtemTableSeeder extends Seeder
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

        $atem = [
            'name' => 'Atem Ndobs',
            'username' => 'atem',
            'tagline' => 'Software Engineer',
            'email' => 'bamarktfact@gmail.com',
            'email_verified_at' => now(),
            'formatted_address' => 'Moltkestraße 55, 40477, Düsseldorf',
            'about' => 'I am a software Engineer Based in Dusseldorf and I am having lots of fun coding ',
            'password' => '$2y$10$UZjGPBj7DIMdXIHhGz63UeMHchNWlIhDV7qqTgIw19GPcMuVDGKhi', // pass1234
            'avatar' => 'https://www.gravatar.com/avatar/'.md5(strtolower('bamarktfact@gmail.com')).'jpg?s=200&d=mm',
            'remember_token' => Str::random(10),
            'available_to_hire' => true,
            'location' =>  DB::raw($atemLocation),
            'uuid' => $faker->uuid,
            'created_at' => new \DateTime('now'),
            'updated_at' => new \DateTime('now')
        ];
        \DB::table('users')->insert($atem);
    }
}
