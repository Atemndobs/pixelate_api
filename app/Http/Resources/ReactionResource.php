<?php

namespace App\Http\Resources;

use Cog\Laravel\Love\Reactant\ReactionCounter\Models\ReactionCounter;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
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
          //  "reaction_type_id" => $this->reaction_type_id,
          //  "reaction_type" => ReactionType::where('reaction_type_id', 1),
            "count" => ReactionCounter::all()->countBy('reaction_type_id')
            ];
    }
}
