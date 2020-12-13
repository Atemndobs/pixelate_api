<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {



        return [
            "id" => $this->id,
            "caption" => $this->caption,
            "location" => $this->location,
            'imageUrl'=> $this->imageUrl,
            'likes'=>new LikeResource($this),
            'new_comment' => $this->comments->last()?:'',
            'comments_count' => $this->comments->count(),
            'comments'=> $this->comments,
            "created_dates" => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            "updated_dates" => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
            'user'=> new UserResource($this->whenLoaded('user')),
          //  'comments'=> CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
