<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeSlotTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_slot_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("consultant_id");
            $table->foreign("consultant_id")->references("id")->on("consultant_requests")->cascadeOnDelete()->cascadeOnUpdate();
            $table->text("description");
            $table->string("slot_duration");
            $table->integer("Waiting_time");
            $table->date("date_from")->default(2000/01/01);
            $table->date("date_to")->default(3000/01/01);
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
        Schema::dropIfExists('time_slot_types');
    }
}
