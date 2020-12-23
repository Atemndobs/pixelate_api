<?php


namespace App\Repositories\Eloquent;


use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Criteria\CriteriaInterface;
use Illuminate\Support\Arr;

abstract class BaseRepository implements BaseRepositoryInterface, CriteriaInterface
{

    protected $model;

    /**
     * BaseRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = $this->getModelClass();
    }


    public function all()
    {
        return $this->model->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    // You could do FindWhereFirstOrFail and FindWhereFirst
    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }



    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data)
    {
        $user = $this->model->create($data);
        return $user;
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
       return $record->delete();
    }

    public function getModelClass()
    {
        if (!method_exists($this, 'model')){
            throw new ModelNotDefined();
        }

        return app()->make($this->model());
    }

    public function withCriteria(...$criteria){

        die(json_encode($this->model));

        $criteria = Arr::flatten($criteria);
        foreach ($criteria as $criterion){
            $this->model = $criterion->apply($this->model);
        }
        return $this;
    }
}
