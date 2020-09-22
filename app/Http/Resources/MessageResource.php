<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Message */
class MessageResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'last_read' => $this->last_read,
            'deleted' => $this->trashed(),
            "dates" => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            "deleted_at" => $this->trashed()? $this->deleted_at->diffForHumans():null,


            'user_id' => $this->user_id,
            'chat_id' => $this->chat_id,

            'chat' => new ChatResource($this->whenLoaded('chat')),
            'sender' => new UserResource($this->sender),
        ];
    }
}
