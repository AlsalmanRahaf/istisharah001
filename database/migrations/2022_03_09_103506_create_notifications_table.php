<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("body");
            $table->boolean("type")->comment("1-user 2-admin");
            $table->boolean("status")->comment("1-not seen 2-seen ");
            $table->unsignedBigInteger("user_id");
            $table->boolean("is_sent")->default(0);
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('notifications');
    }
}
