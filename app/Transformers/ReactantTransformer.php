<?php

namespace App\Transformers;

use App\Models\User;
use Cog\Laravel\Love\Reactant\Models\Reactant;
use Flugg\Responder\Transformers\Transformer;

class ReactantTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'reactions' => ReactionTransformer::class
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [
        'reactions' => ReactionTransformer::class
    ];

    /**
     * Transform the model.
     *
     * @return array
     */
    public function transform(Reactant $reactant)
    {
        return [
            'id' => (int) $reactant->id,
            //'likes_count' => ($reactant->reactions->countBy('id'))->first(),
            'dislikes_count' => null,
            'icon_class' => 'eva-heart-outline',
            'color' => 'black',

            // 'reaction_count' => $reactant->reactions->count(),
           // 'is_liked' => $reactant->isReactedBy($users->getLoveReacter())
        ];
    }
}
