<?php
namespace Database\Seeders;
use App\Models\Chat;
use CommentsTableSeeder;
use DesignsTableSeeder;
use Illuminate\Database\Seeder;
use LikesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LikesTableSeeder::class);
     //   $this->call(DesignsTableSeeder::class);
     //   $this->call(\ChatsTableSeeder::class);
      //  $this->call(\MessagesTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(\InvitationsTableSeeder::class);
        $this->call(TradeSeeder::class);

    }
}
