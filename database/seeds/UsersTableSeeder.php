<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
/*        // How many genres you need, defaulting to 10
        $userCount = (int)$this->command->ask('How many users do you need ?', 10);

        // Ask range for film per user needed
        $r = 0 . '-' . 10;
        $filmRange = $this->command->ask('How many films per user do you need ?', $r);
        $this->command->info("Creating {$userCount} users each having a film range of {$filmRange}.");

        // Create the Users
        $users = factory(App\User::class, $userCount)->create();

        // Create a range of films for each users
        $users->each(function($user) use ($filmRange){
            factory(App\Film::class, $this->count($filmRange))
                ->create(['user_id' => $user->id]);
        });

        $this->command->info('Users and Films Created!');*/




        factory(\App\Models\User::class)->create();
       // factory(\App\Models\User::class, 10)->create();
    }
}
