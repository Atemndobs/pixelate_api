<?php

namespace App\Transformers;

use Cviebrock\EloquentTaggable\Models\Tag;
use Flugg\Responder\Transformers\Transformer;

class TagTransformer extends Transformer
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
     * @param Tag $tag
     * @return array
     */
    public function transform(Tag $tag)
    {
        return [
            'id' => (int) $tag->id,
            'name' => $tag->name
        ];
    }
}
