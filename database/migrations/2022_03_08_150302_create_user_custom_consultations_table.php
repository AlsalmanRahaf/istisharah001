<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_custom_consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("room_id");
            $table->unsignedBigInteger("custom_consultation_id");
            $table->unsignedBigInteger("user_id");
            $table->boolean("status")->default(1)->comment("1 - booked up 2-available ");
            $table->boolean("consultation_status")->default(1)->comment("1-not read 2 -readable  3-follow up  4-not important 5-completed");
            $table->foreign("custom_consultation_id")->references("id")->on("custom_consultations")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('user_custom_consultations');
    }
}
