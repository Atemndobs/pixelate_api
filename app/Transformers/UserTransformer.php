<?php

namespace App\Transformers;

use App\Models\User;
use Flugg\Responder\Transformers\Transformer;
use GeoJson\Geometry\Geometry;

class UserTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'followers' => FollowerTransformer::class,
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [
        'followers' => FollowerTransformer::class,
    ];

    /**
     * Transform the model.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $authUser = auth()->user();
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email'  => $user->email,
            'photo_url' => $user->avatar ?? $user->photo_url,
            'formatted_address'=> $user->formatted_address,
            'available_to_hire'  => $user->available_to_hire?'Yes':'No',
            "tagline" => $user->tagline,
            "about" => $user->about,
            "local" => $user->location,
            "location" => [
                'latitude' => $user->location->getLat(),
                'longitude' => $user->location->getLng()
            ],
            'follower_count'=> $user->followers->count(),
            'following_count'=> $user->following->count(),
            'is_user_following' =>$authUser? $authUser->isFollowing($user):false,
            'post_count' => $user->posts->count(),
            'posts' => $user->posts->count(),
        ];
    }
}
