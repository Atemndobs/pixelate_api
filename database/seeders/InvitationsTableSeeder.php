<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvitationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Invitation::factory()->count(10)->create();
    }
}
