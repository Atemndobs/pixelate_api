<?php


namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

class CommentRepository extends \Prettus\Repository\Eloquent\BaseRepository implements CommentRepositoryInterface
{
    public function model()
    {
        return Comment::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
