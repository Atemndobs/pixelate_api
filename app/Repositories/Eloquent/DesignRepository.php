<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Repositories\Contracts\DesignRepositoryInterface;


class DesignRepository extends BaseRepository implements DesignRepositoryInterface
{
    public function model()
    {
        return Design::class;
    }

    public function applyTags($id, array $data)
    {
        $design = $this->find($id);
       return $design->retag($data);
    }

    public function addComment($design_id, array $data)
    {
        // Get design to create comment for
        $design = $this->find($design_id);

        $comment = $design->comments()->create($data);

        return $comment;
    }

    public function like($id)
    {

        $design = $this->model->findOrFail($id);

        if ($design->isLikedByUser(auth()->id())){
         return   $design->unlike();
        }
        else {
        return    $design->like();
        }

    }

    public function isLikedByUser($id)
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(auth()->id());
    }
}
