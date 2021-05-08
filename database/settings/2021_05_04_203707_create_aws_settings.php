<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateAwsSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('aws.app_id', '');
        $this->migrator->add('aws.app_key', '');
        $this->migrator->add('aws.app_secret', '');
        $this->migrator->add('aws.access_key', '');
        $this->migrator->add('aws.secret_access_key', '');
        $this->migrator->add('aws.default_region', '');
        $this->migrator->add('aws.bucket', '');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('settings')->where('group', 'aws')
            ->update(['payload', '']);
    }
}
