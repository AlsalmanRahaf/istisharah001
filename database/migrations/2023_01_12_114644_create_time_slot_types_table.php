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
            $table->foreign("consultant_id")->references("id")->on("consultant_requests")->cascadeOnUpdate()->cascadeOnDelete();
            $table->text("description");
            $table->string("slot_duration");
            $table->date("date_from");
            $table->date("date_to");
            $table->integer("wating_time");

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
