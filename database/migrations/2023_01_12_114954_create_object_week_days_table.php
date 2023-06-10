<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectWeekDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_week_days', function (Blueprint $table) {
            $table->id();
            $table->time("time_from");
            $table->time("time_to");
            $table->tinyInteger("week_day_number");
            $table->string("week_day_en_name");
            $table->string("week_day_ar_name");
            $table->tinyInteger("is_off");
            $table->foreign("time_slot_type_id")->references("id")->on("time_slot_types")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('object_week_days');
    }
}
