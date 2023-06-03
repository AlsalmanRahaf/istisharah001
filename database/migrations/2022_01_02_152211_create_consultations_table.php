<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    // type of consulataion  add
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("consultant_id");
            $table->unsignedBigInteger("room_id");
//            $table->integer("type")->comment("1-normal 2-Pharmacist 3-nutrition 4-diabetes")->default(1);
            $table->boolean("status")->default(1)->comment("1 - booked up 2-available 3-block");
            $table->boolean("consultations_status")->default(0)->comment("1-not read 2 -readable  3-follow up  4-not important 5-completed 6-vip");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("consultant_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('consultations');
    }
}
