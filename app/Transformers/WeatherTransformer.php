<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Weather;

/**
 * Class WeatherTransformer.
 *
 * @package namespace App\Transformers;
 */
class WeatherTransformer extends TransformerAbstract
{
    /**
     * Transform the Weather entity.
     *
     * @param Weather $model
     *
     * @return array
     */
    public function transform(Weather $model)
    {
        return [
            'id'         => (int) $model->id,

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
