<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Cog\Laravel\Love\Reactant\ReactionCounter\Models\ReactionCounter;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Illuminate\Http\Resources\Json\JsonResource;


class CommentResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
/*        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'commentable' => $this->commentable,
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
            'commenter' => $this->commenter,
            'commenter_id' => $this->commenter_id,
            'commenter_type' => $this->commenter_type,
            'childComments' => Comment::where('commentable_id',$this->id)
                ->where('commentable_type', 'like', '%Comment')->get(),
            'reacter' => $this->reacter,
            'reacter_id' => $this->reacter_id?:'',
            'reaction_count' => $this->reaction_count,
            'reaction_type_id' => $this->reaction_type_id,
            'reaction_type' => $this->reaction_type,
            //'reactions' => Reaction::where(['reactant_id' => $this->love_reactant_id])->get(),
           // "reaction_count" => ReactionCounter::all()->countBy('reaction_type_id')->where('reacter_id', $this->reacter_id),
            // "reaction_count" => ReactionCounter::all()->countBy('reaction_type_id'),
            'created_dates' => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            'updated_dates' => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
        ];*/
        $comments = Comment::where('commentable_id', $this->commentable_id )
            ->with([
                //'loveReactant.reactions.reacter.reacterable',
               // 'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                //'loveReactant.reactionTotal',
            ])
            ->get();

        return [
          'id' =>  $this->id,
            'comment' => $this->comment,
            'commenter' => $this->commenter->name,
            'childComments' => Comment::where('commentable_id',$this->id)
                ->where('commentable_type', 'like', '%Comment')->get(),
           // 'reacter' => $this->reacter,
            'reaction_count' => $comments->where('id' , $this->id)->first()->loveReactant->reactionCounters->countBy('reaction_type_id'),
            'reaction' => $comments->where('id' , $this->id)->first()->loveReactant->reactionCounters,
            'created_dates' => [
                "created_at_human" => $this->created_at->diffForHumans(),
                "created_at" => $this->created_at,
            ],
            'updated_dates' => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
        ];
    }
}



