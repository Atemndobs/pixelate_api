<?php

namespace App\Presenters;

use App\Transformers\WeatherTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class WeatherPresenter.
 *
 * @package namespace App\Presenters;
 */
class WeatherPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new WeatherTransformer();
    }
}
