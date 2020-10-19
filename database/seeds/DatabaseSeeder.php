<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
     //   $this->call(JohnDoesTableSeeder::class);
        $this->call(LikesTableSeeder::class);
        $this->call(DesignsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(TeamsTableSeeder::class);


    }
}
