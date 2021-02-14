<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "name" => $this->name,
            "username" => $this->username,
            'email'=>$this->email,
            'photo_url' => $this->photo_url,
            $this->mergeWhen(auth()->check() && auth()->id() == $this->id, [
                "email" => $this->email,
                "email_verified_at" => $this->email_verified_at,
            ]),
            "tagline" => $this->tagline,
            "location" => $this->location,
            "about" => $this->about,
          //  'follows' => $this->follow,
           // 'followers' => $this->followers,
            'follow'=>$this->followers->map(function ($man) {
                return [
                  // 'id' => $man->id,
                  // 'name' => $man->name,
                   'is_user_following'=> $this->isFollowing($man),
                   'follower_count' => $this->followers->count(),
                   'following_count'=> $this->following->count(),
                   'follower_details' => $man->pivot,
                ];
            }),

           // 'designs'=>DesignResource::collection($this->whenLoaded('designs')),
           // "formatted_address" => $this->formatted_address,
           // "available_to_hire" => $this->available_to_hire,
            "created_dates" => [
                "created_at" => $this->created_at->diffForHumans(),
                "updated_at" => $this->updated_at->diffForHumans()
            ]
        ];
    }
}
