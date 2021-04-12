<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\WeatherRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Weather;
use App\Validators\WeatherValidator;

/**
 * Class WeatherRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WeatherRepository extends BaseRepository implements WeatherRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Weather::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return WeatherValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
