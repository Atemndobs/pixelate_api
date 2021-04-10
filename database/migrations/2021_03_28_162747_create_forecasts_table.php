<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateForecastsTable.
 */
class CreateForecastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid')->nullable();
            $table->float('lat');
            $table->float('lon');
            $table->string('timezone');
            $table->integer('timezone_offset');
            $table->json('current');
            $table->json('hourly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forecasts');
    }
}
