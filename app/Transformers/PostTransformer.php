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
        $id = auth()->check() ? auth()->id() : request()->user_id;
        $authUser = User::find($id);

        return [
            'id' => (int) $post->id,
           // 'user_id' => $authUser,
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
            'is_user_following' => (bool)$authUser->isFollowing($post->user),
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

    public function transformLikes(Post $post)
    {
        $reactions = $post->getLoveReactant()->getReactions();
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
        $comment = $post->comments->last();
        return [
            'id' => (int) $post->id,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'commenter' => [
                   'id' => $comment->commenter->id,
                   'name' => $comment->commenter->name,
                   'photo_url' => $comment->commenter->photo_url,
                   'username' => $comment->commenter->username,
                ]
            ],
            'comment_count' => $post->comments->count(),
            'created_dates' => [
                "created_at_human" => $comment->created_at->diffForHumans(),
                "created_at" => $comment->created_at,
            ],
            'updated_dates' => [
                "updated_at_human" => $comment->updated_at->diffForHumans(),
                "updated_at" => $comment->updated_at,
            ],
        ];
    }

    public function livePost(Post $post)
    {
        return [
            $this->transform($post),
            $this->load,
        ];

    }
}
