<?php

namespace App\Transformers;

use App\Http\Resources\LikeResource;
use App\Models\Post;
use App\Models\User;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Flugg\Responder\Transformers\Transformer;
use function PHPUnit\Framework\isEmpty;

class PostTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'user'=> UserTransformer::class,
        'comments'=> CommentTransformer::class,
        'loveReactant' => ReactantTransformer::class,
        'tags' => TagTransformer::class
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [
        'user'=> UserTransformer::class,
        'comments'=> CommentTransformer::class,
        'loveReactant' => ReactantTransformer::class,
        'tags' => TagTransformer::class
    ];

    /**
     * Transform the model.
     *
     * @param Post $post
     * @return array
     */
    public function transform(Post $post)
    {
        $authUser = User::find(request()->user_id);

        return [
            'id' => (int) $post->id,
            'user_id' => request()->user_id,
            "caption" => $post->caption,
            "location" => $post->location,
            'imageUrl'=> $post->imageUrl,
            'likes_count' => !is_null($post->likers)?count($post->likers):null,
            'is_liked' =>  !is_null($post->likers)?
                in_array((int)request()->user_id, $post->likers, true)
                :false,
            'likers' => $post->likers,
            'new_comment' =>$post->latest_comment,
            'comments_count' => $post->comments->count(),
            'is_user_following' => $authUser->isFollowing($post->user),
            "created_dates" => [
                "created_at_human" => $post->created_at->diffForHumans(),
                "created_at" => $post->created_at,
            ],
            "updated_dates" => [
                "updated_at_human" => $post->updated_at->diffForHumans(),
                "updated_at" => $post->updated_at,
            ],
        ];
    }

    public function getUser($id)
    {
          return (User::find($id))->loveReacter;
    }

    public function transformLikes(Post $post)
    {
        $reactions = $post->getLoveReactant()->getReactions();
       // die(json_encode($post->likers));
        return [
            'id' => (int) $post->id,
            'likes_count' => $reactions->count(),
            'reaction_type' => $post->reaction_type,
            'likes' => $reactions->map(function ($reaction) {
                return [
                   'id' => $reaction->id,
                   'reaction_type_id' => $reaction->reaction_type_id,
                 //  'reactant_id' => $reaction->reactant_id,
                  // 'reacter_id' => $reaction->reacter_id,

                ];
            })
        ];
    }

    public function transformComment(Post $post)
    {
        return [
            'id' => (int) $post->id,
            'comment' => [
                'id' => $post->comments->last()->id,
                'comment' => $post->comments->last()->comment,
                'commenter' => [
                   'id' => $post->comments->last()->commenter->id,
                   'name' => $post->comments->last()->commenter->name,
                   'photo_url' => $post->comments->last()->commenter->photo_url,
                   'username' => $post->comments->last()->commenter->username,
                ]

            ],
            //'new_comment' => $post->latest_comment,
            'comment_count' => $post->comments->count(),
            'created_dates' => [
                "created_at_human" => $post->created_at->diffForHumans(),
                "created_at" => $post->created_at,
            ],
            'updated_dates' => [
                "updated_at_human" => $post->updated_at->diffForHumans(),
                "updated_at" => $post->updated_at,
            ],
        ];
    }
}
