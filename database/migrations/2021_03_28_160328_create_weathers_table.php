<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weathers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->uuid('uuid')->nullable();
            $table->float('dt');
            $table->string('name');
            $table->string('base');
            $table->integer('timezone');
            $table->integer('cod');
            $table->integer('visibility');

            $table->json('data');
            $table->json('weather');
            $table->json('coord');
            $table->json('main');
            $table->json('clouds');
            $table->json('sys');

            $table->json('wind');

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
        Schema::dropIfExists('weathers');
    }
}
