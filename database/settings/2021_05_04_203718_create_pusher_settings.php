<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePusherSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pusher.app_id', '');
        $this->migrator->add('pusher.app_key', '');
        $this->migrator->add('pusher.app_secret', '');
        $this->migrator->add('pusher.app_cluster', '');
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->where('group', 'pusher')
            ->update(['payload', '']);
    }
}
