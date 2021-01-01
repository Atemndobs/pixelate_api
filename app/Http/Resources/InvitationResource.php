<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Invitation */
class InvitationResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'recipient_email' => $this->recipient_email,
            'sender_id' => $this->sender_id,
            'token' => $this->token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'team_id' => $this->team_id,

            'team' => new TeamResource($this->whenLoaded('team')),
        ];
    }
}
