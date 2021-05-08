<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateAlgoliaSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('algolia.app_id', '');
        $this->migrator->add('algolia.app_secret', '');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->where('group', 'algolia')
            ->update(['payload', '']);
    }
}
