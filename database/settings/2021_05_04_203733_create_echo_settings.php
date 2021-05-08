<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateEchoSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('echo.app_id', 'local');
        $this->migrator->add('echo.app_key', 'local');
        $this->migrator->add('echo.app_secret', 'local');
        $this->migrator->add('echo.app_cluster', 'local');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->where('group', 'echo')
            ->update(['payload', '']);
    }
}
