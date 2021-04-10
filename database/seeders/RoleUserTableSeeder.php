<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        User::findOrFail(1)->roles()->sync(1);

        foreach (User::all() as $user) {
            if ($user->id === 1) {
                continue;
            }

            $user->roles()->sync(2);

        }
    }
}
