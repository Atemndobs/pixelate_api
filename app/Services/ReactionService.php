<?php


namespace App\Services;


use App\Http\Resources\PostResource;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use function GuzzleHttp\Promise\rejection_for;

class ReactionService
{
    private $user;

    /**
     * ReactionService constructor.
     * @param $user
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function processReaction($type, $existingReactant)
    {
        try {
            $reactionType = ReactionType::fromName($type);
        } catch (\Exception $exception) {
            return Response([
                'error' => $exception->getMessage(),
            ], 404);
        }

        $reacter = $this->user->getLoveReacter();;
        $reactant = $existingReactant->getLoveReactant();
        $reactantId = $existingReactant->getLoveReactant()->getId();

        $reactionTypeId = $reactionType->getId();
        $existing_reaction = $reacter->getReactions()
            ->where('reactant_id',$reactantId)->all();
        $existing_like = $reacter->getReactions()
            ->where('reactant_id',$reactantId )
            ->where('reaction_type_id',1)
            ->first();

        $existing_disLike = $reacter->getReactions()
            ->where('reactant_id',$reactantId )
            ->where('reaction_type_id',2)
            ->first();

        $like = ReactionType::fromName('Like');
        $dislike = ReactionType::fromName('Dislike');
        $reaction_type = '';
        if (!empty($existing_reaction) ){
            if (!empty($existing_like))
            {
                if ((int)$reactionTypeId ===2) {
                    // echo 'LIKE EXISTS but want to Dislike';
                    $reacter->unreactTo($reactant, $like);
                    $reacter->reactTo($reactant, $reactionType);
                    $reaction_type = 'Dislike';

                } else{
                    // echo 'LIKE EXISTS so Unlike';
                    $reacter->unreactTo($reactant, $reactionType);
                    $reaction_type = 'unLike';

                }
            }

            if (!empty($existing_disLike))
            {
                if ((int)$reactionTypeId ===1) {
                    //  echo 'DISLIKE EXISTS But want to Like';
                    $reacter->unreactTo($reactant, $dislike);
                    $reacter->reactTo($reactant, $reactionType);
                    $reaction_type = 'Like';
                } else{
                    //  echo 'DISLIKE EXISTS so unDislike';
                    $reacter->unreactTo($reactant, $dislike);
                    $reaction_type = 'unDislike';
                }
            }


        }else{
            // echo('never liked or disliked before so REACT');
            $reacter->reactTo($reactant, $reactionType);
            $reaction_type = $reactionType->getName();
        }

        $existingReactant['reaction_type'] = $reaction_type;
        $existingReactant->reacter = $reacter;

        return $existingReactant;

    }
}
