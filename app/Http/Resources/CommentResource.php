<?php

namespace App\Http\Resources;

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
            'comments' => ''
        ];
    }
}


//         return [
//            'id' => $this->id,
//            'comment' => $this->body,
//            'commentable_id' => $this->commentable_id,
//            'commentable' => $this->commentable,
//            'commentable_type' => $this->commentable_type,
//            'commenter' => $this->commenter,
//            'commenter_id' => $this->commenter_id,
//            'commenter_type' => $this->commenter_type,
//            'created_at_dates' => [
//                "created_at_human" => $this->created_at->diffForHumans(),
//                "created_at" => $this->created_at,
//            ],
//            'updated_at_dates' => [
//                "updated_at_human" => $this->updated_at->diffForHumans(),
//                "updated_at" => $this->updated_at,
//            ],
//          //  'user' => new UserResource($this->user),
//        ];
