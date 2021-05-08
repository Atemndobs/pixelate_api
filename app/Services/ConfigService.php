<?php


namespace App\Services;

use App\Models\Setting;
use App\Settings\AlgoliaSettings;
use App\Settings\AwsSettings;
use App\Settings\EchoSettings;
use App\Settings\GeneralSettings;
use App\Settings\PusherSettings;
use App\Settings\WeatherSettings;

class ConfigService
{
    private GeneralSettings $generalSettings;
    private PusherSettings $pusherSettings;
    private WeatherSettings $weatherSettings;
    private AlgoliaSettings $algoliaSettings;
    private AwsSettings $awsSettings;

    /**
     * ConfigService constructor.
     * @param GeneralSettings $generalSettings
     * @param PusherSettings $pusherSettings
     * @param WeatherSettings $weatherSettings
     * @param AlgoliaSettings $algoliaSettings
     * @param AwsSettings $awsSettings
     */
    public function __construct(
        GeneralSettings $generalSettings,
        PusherSettings $pusherSettings,
        WeatherSettings $weatherSettings,
        AlgoliaSettings $algoliaSettings,
        AwsSettings $awsSettings
    ) {
        $this->generalSettings = $generalSettings;
        $this->pusherSettings = $pusherSettings;
        $this->weatherSettings = $weatherSettings;
        $this->algoliaSettings = $algoliaSettings;
        $this->awsSettings = $awsSettings;
    }


    public function getGroups()
    {
        return [
            $this->generalSettings::group(),
            $this->pusherSettings::group(),
            $this->weatherSettings::group(),
            $this->awsSettings::group(),
            $this->algoliaSettings::group()
        ];
    }

    public function getNames($group)
    {
        return Setting::where('group', $group)->get()->map(function ($setting) {
            return $setting->name;
        });
    }
}
