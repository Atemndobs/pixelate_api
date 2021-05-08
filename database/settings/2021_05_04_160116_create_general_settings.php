<?php

use Illuminate\Database\Schema\Blueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Pixelate');
        $this->migrator->add('general.site_active', true);
        $this->migrator->add('general.timezone', 'Europe/Berlin');
        $this->migrator->add('general.jwt_secret', env('JWT_SECRET'));
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->where('group', 'general')
            ->update(['payload' => '']);
    }
}

