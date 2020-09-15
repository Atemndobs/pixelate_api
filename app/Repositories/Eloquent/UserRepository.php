<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function model()
    {
        return User::class;
    }
}
