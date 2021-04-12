<?php

namespace App\Transformers;

use App\Models\Comment;
use Flugg\Responder\Transformers\Transformer;

class CommentTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment)
    {
/*

        $comments = Comment::where('commentable_id', $comment->commentable_id)
            ->with([
                'loveReactant.reactionCounters',
            ])
            ->get();

        $childComments = Comment::where('commentable_id', $comment->id)
            ->where('commentable_type', 'like', '%Comment')->get();


        $reactions = $comments->where('id', $this->id)
            ->first()->loveReactant->reactionCounters;

        $reaction_count = [];
        foreach ($reactions as $reaction) {
            $reaction_type_id = $reaction->reaction_type_id;
            $count = $reaction->count;
            $reaction_count[] = $count;
        }


        }*/

        $reactions = $comment->loveReactant->reactionCounters;

        return [
            'id' => (int) $comment->id,
            'comment' => $comment->comment,
            'commenter' => [
                'id' =>  $comment->commenter->id,
                'name'=> $comment->commenter->name,
                "username" =>  $comment->commenter->username,
                'formatted_address' => $comment->commenter->formatted_address,
                'photo_url' => $comment->commenter->avatar ?? $comment->commenter->photo_url,
            ],
            'childComments' => $comment->children->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    "comment" =>  $comment->comment,
                    "commenter_id" =>  $comment->commenter_id,
                    'commenter'=> [
                        'id' =>  $comment->commenter->id,
                        'name'=> $comment->commenter->name,
                        "username" =>  $comment->commenter->username,
                        'formatted_address' => $comment->commenter->formatted_address,
                        'photo_url' => $comment->commenter->photo_url,
                    ]
                ];
            }),
            'parent' => [
                'id' => $comment->parent->id??null,
                "comment" =>  $comment->parent->comment??null,
                "commenter_id" =>  $comment->parent->commenter_id??null,
                'commenter'=> $comment->parent->commenter->name??null,
            ],
            'reaction_count' => $reactions->count(),
            'reactions' => $reactions,
            //'reaction_count_ids' => $reactions->countBy('reaction_type_id'),
            //'reaction' => $reactions,
            //'love_reactant' => $comments->where('id' , $comment->id)->first()->loveReactant,
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

    public function transformComment(Comment $comment)
    {
        return [
            'id' => (int) $comment->id,
            'comment' => $comment->comment,
            'children_count' => $comment->children_count,
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
}
