<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\User;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {

        $likes = Reaction::all()
            ->where('reaction_type_id', 1)
            ->where('reactant_id', $this->id);

        $user_id = (int)$request->user_id ;
        $user = User::find($user_id);

        $author = User::find($this->user_id);

        return [
            "id" => $this->id,
            'user_id' =>$user_id,
            'author' => [
                'id' => $author->id,
                'name' => $author->name,
                'avatar'=> $author->photo_url,
                'follow'=>$author->followers->map(function ($man) use ($user) {
                    return [
                        'is_user_following'=> $user->isFollowing($man),
                        'follower_count' => $user->followers->count(),
                        'following_count'=> $user->following->count(),
                        //'follower_details' => $man->pivot,
                    ];
                }),
            ],
            "caption" => $this->caption,
            "location" => $this->location,
            'imageUrl'=> $this->imageUrl,
            'likes'=> new LikeResource($this),
            'reactions' => $likes,
            'new_comment' => CommentResource::collection($this->comments)->last()?:'',
            'comments_count' => $this->comments->count(),
          //  'comments'=> Comment::all(),
            'comments'=> CommentResource::collection($this->whenLoaded('comments')),
            "created_dates" => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            "updated_dates" => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
        ];
    }
}
