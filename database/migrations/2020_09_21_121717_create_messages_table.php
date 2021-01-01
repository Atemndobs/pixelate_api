<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('messages')){
            Schema::create('messages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('body');
                $table->dateTime('last_read')->nullable();
                $table->bigInteger('user_id')->unsigned();
                $table->bigInteger('chat_id')->unsigned();
                $table->softDeletes();
                $table->timestamps();

               # $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
               # $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('messages');
    }
}
