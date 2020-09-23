<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Contracts\CriterionInterface;
use Illuminate\Database\Eloquent\Model;

class EagerLoad implements CriterionInterface
{
    /**
     * @var array
     */
    protected array $relationships;

    /**
     * EagerLoad constructor.
     * @param $relationships
     */
    public function __construct($relationships)
    {
        $this->relationships = $relationships;
    }

    public function apply($model)
    {
        return $model->with($this->relationships);
    }
}
