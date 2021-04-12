<?php


namespace App\Repositories\Eloquent\Criteria;




use App\Repositories\Contracts\CriterionInterface;

class ForDesign implements CriterionInterface
{

    protected $design_id;

    /**
     * ForDesign constructor.
     * @param $design_id
     */
    public function __construct($design_id)
    {
        $this->design_id = $design_id;
    }


    public function apply($model)
    {
        return $model->where('id', $this->design_id);
    }



}
