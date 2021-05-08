<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateWeatherSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('weather.api_key', '');
        $this->migrator->add('weather.api_base_url', '');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->where('group', 'weather')
            ->update(['payload', '']);
    }
}
