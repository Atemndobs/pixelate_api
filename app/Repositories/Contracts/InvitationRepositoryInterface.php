<?php

namespace App\Repositories\Contracts;

interface InvitationRepositoryInterface
{
    /**
     * BaseRepository constructor.
     * @throws \Exception
     */
    public function __construct();

    public function all();

    public function find($id);

    public function findWhere($column, $value);

    public function findWhereFirst($column, $value);

    public function paginate($perPage = 10);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getModelClass();

    public function withCriteria(...$criteria);

    public function addUserToTeam($team, $user_id);
    public function removeUserFromTeam($team, $user_id);

}
