<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Team */
class TeamResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'designs_count' => $this->designs->count(),
            'members_count' => $this->members->count(),
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'total_members' => $this->members->count(),
            'designs' => DesignResource::collection($this->designs),
            "created_dates" => [
                "created_at" => $this->created_at->diffForHumans(),
                "updated_at" => $this->updated_at->diffForHumans()
            ],
            'owner' => new UserResource($this->owner),
            'member'=> UserResource::collection($this->members)
        ];
    }
}
