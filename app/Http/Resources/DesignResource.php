<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "slug" => $this->slug,
            "disk" => $this->disk,
            "is_live" => $this->is_live,
            'images'=> $this->images,
            'likes_count' => $this->likes->count(),
            "uploaded_successful" => $this->uploaded_successful,
            "tag_list"=>[
                'tag' => $this->tagArray,
                'normalized' => $this->tagArrayNormalized,
            ],
            "created_dates" => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            "updated_dates" => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
            'team' => $this->team ? [
                'name' => $this->team->name,
                'slug' => $this->team->slug,
            ] : null,
            'user'=> new UserResource($this->whenLoaded('user')),
            'comments'=> CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
