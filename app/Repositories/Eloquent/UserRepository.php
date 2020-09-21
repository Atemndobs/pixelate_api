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

    public function findByEmail($email)
    {
     //   return $this->model->where(['email' => $email]);
     //   dd($this->model);
        return $this->model->where('email' , $email)->first();
    }
}
