<?php

namespace App\Http\Resources;

use Cog\Laravel\Love\Reaction\Models\Reaction;
use Illuminate\Http\Resources\Json\JsonResource;


class CommentResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'commentable' => $this->commentable,
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
            'commenter' => $this->commenter,
            'commenter_id' => $this->commenter_id,
            'commenter_type' => $this->commenter_type,
//            'likes'=>new LikeResource($this),
            'reacter' => $this->reacter,
            'reacter_id' => $this->reacter_id?:'',
            'reactions' => Reaction::all()
                ->where('reaction_type_id', 1)
                ->where('reactant_id', $this->id),
            'created_at_dates' => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            'updated_at_dates' => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
            //  'user' => new UserResource($this->user),
        ];
    }
}



