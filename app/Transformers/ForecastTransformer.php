<?php

namespace App\Transformers;

use App\Models\Forecast;
use Flugg\Responder\Transformers\Transformer;

class ForecastTransformer extends Transformer
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
     * @param Forecast $forecast
     * @return array
     */
    public function transform(Forecast $forecast)
    {
        return [
            'id' => (int) $forecast->id,
        ];
    }
}
