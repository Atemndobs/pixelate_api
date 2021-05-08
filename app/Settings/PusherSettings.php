<?php


namespace App\Settings;


class PusherSettings extends \Spatie\LaravelSettings\Settings
{

    public string $app_id;
    public string $app_key;
    public string $app_secret;
    public string $app_cluster;

    public static function group(): string
    {
        return 'pusher';
    }


}
