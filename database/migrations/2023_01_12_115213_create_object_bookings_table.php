<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_bookings', function (Blueprint $table) {
            $table->id();
            $table->integer("reservation_record_id")->nullable();
            $table->date("date");
            $table->unsignedBigInteger("slot_id");
            $table->unsignedBigInteger("object_id");
            $table->boolean("is_online")->default(0);
            $table->boolean("is_cancelled")->default(0);
            $table->foreign("slot_id")->references("id")->on("time_slots")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("object_id")->references("id")->on("object_details")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('object_bookings');
    }
}
