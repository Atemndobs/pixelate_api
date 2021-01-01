<?php


namespace Tests;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait PostData
{
    public function createPostData()
    {
        return [
            'caption' => 'The Test Post',
            'location' => 'Dusseldorf',
            'imageUrl' => 'https://picsum.photos/id/461/600',
            'user_id' => $this->createUser()->id,
         //   'user_id' => \App\Models\User::factory()->create()->id
        ];
    }

    public function createUser()
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
            'remember_token' => Str::random(10),
            'available_to_hire' => true,
            'location' =>  DB::raw($atemLocation),
            'uuid' => $faker->uuid,
            'created_at' => new \DateTime('now'),
            'updated_at' => new \DateTime('now')
        ];


        $user = User::create($atem);

        return $user;
    }
}
