<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Repositories\Contracts\DesignRepositoryInterface;


class DesignRepository extends BaseRepository implements DesignRepositoryInterface
{
    public function model()
    {
        return Design::class;
    }

}
