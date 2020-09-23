<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Repositories\Contracts\DesignRepositoryInterface;
use Illuminate\Http\Request;


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


    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();
        $query->where('is_live', true);

        // return only designs with comments
        if ($request->has_comments) {
            $query->has('comments');
        }
        // return only designs assigned to team
        if ($request->has_team) {
            $query->has('team');
        }

        //search title and description for provided string

        if ($request->q){
            $query->where(function($q) use ($request){
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%');
            });
        }

        // order the query by  likes or latest first
        if ($request->orderBy == 'likes'){
            $query->withCount('likes')->orderByDesc('likes_count');
        }else {
            $query->latest();
        }

        return $query->get();

    }
}
