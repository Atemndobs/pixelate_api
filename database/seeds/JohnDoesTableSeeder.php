<?php

use Illuminate\Database\Seeder;

class JohnDoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class)->create();
    }
}
