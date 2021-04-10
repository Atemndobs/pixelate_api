<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ForecastRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Forecast;

/**
 * Class ForecastRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ForecastRepository extends BaseRepository implements ForecastRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Forecast::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
