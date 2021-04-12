<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Contracts\CriterionInterface;

class LatestFirst implements CriterionInterface
{

    public function apply($model)
    {
       #  return $model->orderBy('created_at');
        return $model->latest();
    }
}
