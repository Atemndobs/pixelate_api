<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PermissionRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Permission;

/**
 * Class PermissionRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



}
