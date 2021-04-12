<?php

namespace App\Http\Resources;

use Cog\Laravel\Love\Reaction\Models\Reaction;
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
        $reactions = $this->getLoveReactant()->getReactions();
        return [
            'reaction_type' =>  $reactions->map(function ($reaction) {
                return $reaction->reaction_type_id;
            })->first(),
            'icon_class' =>'eva-heart-outline',
            'color' => $this->color?:'black',
            'likes_count' => $reactions->where('reaction_type_id', 1)->count(),
            'dislikes_count' => $reactions->where('reaction_type_id', 2)->count(),
            'reaction_count' => $reactions->count()
        ];
    }
}
