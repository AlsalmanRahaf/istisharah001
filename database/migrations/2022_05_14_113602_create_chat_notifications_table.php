<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("chat_id");
            $table->integer("status")->comment("0-send 1-not send")->default(1);
            $table->foreign("chat_id")->references("id")->on("user_chats")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('chat_notifications');
    }
}
