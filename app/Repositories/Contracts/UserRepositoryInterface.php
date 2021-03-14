<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail($email);
    public function search(Request $request);
}
