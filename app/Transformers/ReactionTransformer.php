<?php

namespace App\Transformers;

use Cog\Laravel\Love\Reaction\Models\Reaction;
use Flugg\Responder\Transformers\Transformer;

class ReactionTransformer extends Transformer
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
     * @param Reaction $reaction
     * @return array
     */
    public function transform(Reaction $reaction)
    {
        return [
            'id' => (int) $reaction->id,
            'reaction_type' => $reaction->reaction_type_id,
            'reacter' => $reaction->reacter->id
        ];
    }
}
