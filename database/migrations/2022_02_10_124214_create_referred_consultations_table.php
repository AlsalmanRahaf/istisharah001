<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferredConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referred_consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("consultation_id");
            $table->unsignedBigInteger("consultant_id");
            $table->unsignedBigInteger("room_id");
            $table->unsignedBigInteger("user_id");
            $table->boolean("status")->default(1)->comment("1-not read 2 -readable 3-not important 4-completed");
            $table->String("type")->comment("1-Pharmacist 2-nutrition 3-diabetes");
            $table->foreign("room_id")->references("id")->on("rooms")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("consultation_id")->references("id")->on("consultations")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("consultant_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();


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
        Schema::dropIfExists('referred_consultations');
    }
}
