<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Weather.
 *
 */
class Weather extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'weathers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        'uuid',
        "dt",
        "name",
        "base",
        "timezone",
        "cod",
        "visibility",

        "data",
        "weather",
        "coord",
        "main",
        "clouds",
        "sys",
        "wind",
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'weather' => 'array',
        'coord' => 'array',
        'main' => 'array',
        'clouds' => 'array',
        'sys' => 'array',
        'wind' => 'array',
    ];
}
