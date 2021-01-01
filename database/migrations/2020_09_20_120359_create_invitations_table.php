<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('invitations')){
            Schema::create('invitations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('recipient_email')->index();
                $table->bigInteger('sender_id')->unsigned();
                $table->bigInteger('team_id')->unsigned();
                $table->string('token');
                $table->timestamps();

                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
