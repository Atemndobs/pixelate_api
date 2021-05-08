<?php


namespace App\Settings;

class GeneralSettings extends \Spatie\LaravelSettings\Settings
{
    public string $site_name;
    public string $site_active;
    public string $timezone;
    public string $jwt_secret;

    public static function group(): string
    {
        return 'general';
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->site_name;
    }


}
