<?php


namespace App\Settings;

class AlgoliaSettings extends \Spatie\LaravelSettings\Settings
{
    public string $app_id;
    public string $app_secret;

    public static function group(): string
    {
        return 'algolia';
    }
}
