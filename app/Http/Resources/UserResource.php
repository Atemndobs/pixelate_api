<?php

namespace App\Http\Resources;

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
      //  return parent::toArray($request);
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
            'designs'=>DesignResource::collection($this->whenLoaded('designs')),

            "tagline" => $this->tagline,
            "location" => $this->location,
            "formatted_address" => $this->formatted_address,
            "available_to_hire" => $this->available_to_hire,
            "about" => $this->about,
            "created_dates" => [
                "created_at" => $this->created_at->diffForHumans(),
                "updated_at" => $this->updated_at->diffForHumans()
            ]
        ];
    }
}
