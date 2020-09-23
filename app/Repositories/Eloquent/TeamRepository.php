<?php


namespace App\Repositories\Eloquent;


use App\Models\Team;
use App\Repositories\Contracts\TeamRepositoryInterface;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    public function model()
    {
        return Team::class;
    }

    public function fetchUserTeams()
    {

        return $this->findWhere('owner_id', auth()->id());

      //  return auth()->user()->teams();
    }
}
