<?php


namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WeatherSettings extends Settings
{
    /**
     * @var string
     */
    public string $api_key;
    /**
     * @var string
     */
    public string $api_base_url;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'weather';
    }
}
