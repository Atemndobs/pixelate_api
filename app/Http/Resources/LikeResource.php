<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
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
            'reaction_type'=>$this->reaction_type?:'',
            "love_reactant_id" => $this->love_reactant_id,
            'likes_count' => $this->getLoveReactant()->getReactions()->where('reaction_type_id', 1)->count(),
            'dislikes_count' => $this->getLoveReactant()->getReactions()->where('reaction_type_id', 2)->count(),
        ];
    }
}
