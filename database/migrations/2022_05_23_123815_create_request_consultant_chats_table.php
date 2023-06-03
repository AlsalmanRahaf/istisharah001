<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestConsultantChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_consultant_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("request_id");
            $table->unsignedBigInteger("room_id");
            $table->unsignedBigInteger("status")->comment("1-active  2-not active");
            $table->foreign("request_id")->references("id")->on("request_consultants")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("room_id")->references("id")->on("rooms")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('request_consultant_chats');
    }
}
