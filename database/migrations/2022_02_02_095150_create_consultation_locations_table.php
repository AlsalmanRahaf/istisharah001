<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("room_id");
            $table->String("country");
            $table->integer("type")->comment("1-normal 2-specialist 3-custom");
            $table->integer("location")->comment("1-inside 2-outSide");
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
        Schema::dropIfExists('consultation_locations');
    }
}
