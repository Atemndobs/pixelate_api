<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Forecast.
 *
 */
class Forecast extends Model implements Transformable
{
    use TransformableTrait;

    public $table = 'forecasts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'lat',
        'lon',
        'timezone',
        'timezone_offset',
        'current',
        'hourly'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'current' => 'array',
        'hourly' => 'array'
    ];

}
