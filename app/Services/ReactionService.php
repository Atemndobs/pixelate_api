<?php


namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Eloquent\PostRepository;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use DB;
use Illuminate\Support\Facades\Log;

class ReactionService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function processReaction($type, $existingReactant)
    {
        $reactionType = $this->getType($type);
        $reactionTypeId = $reactionType->getId();
        $reacter = $this->user->getLoveReacter();
        $reactant = $existingReactant->getLoveReactant();

        $existing_reaction = $this->getAllExistingReactions($existingReactant);
        $existing_react_type = $this->getExistingReaction($type, $existingReactant);
        $existing_disReact_type = $this->getExistingDisReaction($type, $existingReactant);


        // React type, if string has Dis, remove it and make react type else just make react type

        if (str_contains($type, 'Dis')) {
           // echo "INCOMING :: " . $type ."\n";
            $reactName = substr($type, 3);
           // echo "Sanitized => :: " . $reactName ."\n";
            $reactType = ReactionType::fromName($reactName);
        } else {
            $reactType = ReactionType::fromName($type);
        }

        // To make Disreact Type, If string has no Dis, Add dis to it and make Disreact type
        if (!str_contains($type, 'Dis')) {
            //echo "INCOMING :: " . $type ."\n";
            $disReactName = 'Dis'.$type;
           // echo "Sanitized => :: " . $disReactName ."\n";
            $disReactType = ReactionType::fromName($disReactName);
        } else {
            $disReactType = ReactionType::fromName($type);
        }


        $reaction_type = '';

        if (!empty($existing_reaction)) {
            if (!empty($existing_react_type)) {
                if ($reactionTypeId === $disReactType->getId()) {
                    // echo 'LIKE EXISTS but want to Dislike'."\n";
                    $reacter->unreactTo($reactant, $reactType);
                    $reacter->reactTo($reactant, $reactionType);
                    $reaction_type = $type;
                } else {
                    // echo 'LIKE EXISTS so Unlike'."\n";
                    $reacter->unreactTo($reactant, $reactionType);
                    $reaction_type = 'un'.$type;
                }
            }

            if (!empty($existing_disReact_type)) {
                if ($reactionTypeId === $reactType->getId()) {
                    //  echo 'DISLIKE EXISTS But want to Like' ."\n";
                    $reacter->unreactTo($reactant, $disReactType);
                    $reacter->reactTo($reactant, $reactionType);
                    $reaction_type = $type;
                } else {
                    //  echo 'DISLIKE EXISTS so unDislike' ."\n";
                    $reaction_type = 'un'.$type;
                    $reacter->unreactTo($reactant, $reactionType);
                }
            }

            if (empty($existing_react_type) && empty($existing_disReact_type)) {
              //  echo('BRAND NEU REACTION:  React to ' . $type)."\n";

                $reacter->reactTo($reactant, $reactionType);
                $reaction_type = $reactionType->getName();
            }
        } else {
           //  echo('never liked or disliked before so REACT')."\n";
            $reacter->reactTo($reactant, $reactionType);
            $reaction_type = $reactionType->getName();
        }


        if (!str_contains($reaction_type, 'un')) {
            $existingReactant->addLiker();
        } else {
            $existingReactant->removeLiker();
        }

        $existingReactant['reaction_type'] = $reaction_type;
        $existingReactant['reaction_type_id'] = $reactionTypeId;

        $existingReactant->reacter = $reacter;

        return $existingReactant;
    }

    public function getType($type)
    {
        try {
            return ReactionType::fromName($type);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), ['context' => 'critical']);

            return [
                ['error' => 'Reaction type does not Exist : ' .$type]
            ];
        }
    }

    /**
     * @param $existingReactant
     * @return mixed
     */
    public function getAllExistingReactions($existingReactant):array
    {
        $reacter = $this->user->getLoveReacter();
        $reactantId = $existingReactant->getLoveReactant()->getId();

        return $reacter->getReactions()
            ->where('reactant_id', $reactantId)->all();
    }

    /**
     * @param $type
     * @param $existingReactant
     * @return mixed
     */
    public function getExistingReaction($type, $existingReactant)
    {

        $reacter = $this->user->getLoveReacter();
        $reactantId = $existingReactant->getLoveReactant()->getId();

        if (strpos($type, 'Dis') !== false) {
           // echo "INCOMING :: " . $type ."\n";
            $reactName = substr($type, 3);
            $reactionTypeId = ReactionType::fromName($reactName)->getId();
        } else {
            $reactionTypeId = ReactionType::fromName($type)->getId();
        }

        return $reacter->getReactions()
            ->where('reactant_id', $reactantId)
            ->where('reaction_type_id', $reactionTypeId)
            ->first();
    }

    public function getExistingDisReaction($type, $existingReactant)
    {
        $reacter = $this->user->getLoveReacter();
        $reactantId = $existingReactant->getLoveReactant()->getId();
        if (strpos($type, 'Dis') === false) {
           // echo "USE THIS FOR DIS-CASE :: " . $type ."\n";
            $reactName = 'Dis'.$type;
            $reactionTypeId = ReactionType::fromName($reactName)->getId();
        } else {
            $reactionTypeId = ReactionType::fromName($type)->getId();
        }

       // echo "Searching Dis react Type  :: " . $type ."\n";


        $reaction =  $reacter->getReactions()
            ->where('reactant_id', $reactantId)
            ->where('reaction_type_id', $reactionTypeId)
            ->first();

     // Better (Documented)  option for querying existing reaction (to be refactored)
/*        $reaction =  $existingReactant::query()
            ->whereReactedBy($this->user, $type)
            ->get();*/
        return $reaction;
    }
}
