<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultantAdminChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultant_admin_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("consultant_id");
            $table->unsignedBigInteger("admin_id");
            $table->unsignedBigInteger("room_id");
            $table->foreign("consultant_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("admin_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('consultant_admin_chats');
    }
}
