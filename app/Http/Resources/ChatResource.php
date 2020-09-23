<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Chat */
class ChatResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "dates" => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            'is_unread'=> $this->isUnreadForUser(auth()->id()),
            'unread_messages' => $this->unreadMessages(auth()->id()),
            'latest_message' => new MessageResource($this->latest_message),
            'messages_count' => $this->messages->count(),
            'participants_count' => $this->participants->count(),

            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'participants' => UserResource::collection($this->whenLoaded('participants')),
        ];
    }
}
