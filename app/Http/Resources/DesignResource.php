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
            'user'=> new UserResource($this->user),
            "title" => $this->title,
            "description" => $this->description,
            "slug" => $this->slug,
            "disk" => $this->disk,
            "is_live" => $this->is_live,
            'images'=> $this->images,
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
            ]
        ];
    }
}
