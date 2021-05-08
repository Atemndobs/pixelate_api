<?php


namespace App\Settings;

class AwsSettings extends \Spatie\LaravelSettings\Settings
{
    public string $app_id;
    public string $app_key;
    public string $app_secret;
    public string $access_key;
    public string $secret_access_key;
    public string $default_region;
    public string $bucket;

    public static function group(): string
    {
        return 'aws';
    }
}
