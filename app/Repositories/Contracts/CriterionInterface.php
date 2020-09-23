<?php


namespace App\Repositories\Contracts;


interface CriterionInterface
{
    public function apply($model);
}
