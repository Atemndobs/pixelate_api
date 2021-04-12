<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *make
     * @return void
     */
    public function run()
    {
        \App\Models\Comment::factory()->count(10)->create();
    }
}
