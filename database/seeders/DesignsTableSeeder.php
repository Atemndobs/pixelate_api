<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DesignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Design::factory()->count(10)->create();
    }
}
