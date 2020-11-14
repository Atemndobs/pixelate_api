<?php

namespace Database\Seeders;
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

        $this->call(DesignsTableSeeder::class);

        $this->call(TradeSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(LikesTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(InvitationsTableSeeder::class);
        $this->call(ParticipantTableSeeder::class);
        $this->call(MessagesTableSeeder::class);

    }
}
