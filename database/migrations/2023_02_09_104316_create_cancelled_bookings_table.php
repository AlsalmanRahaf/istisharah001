<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancelledBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancelled_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("booking_id");
            $table->morphs("user");
            $table->text("cancellation_reason")->nullable();
            $table->date("cancellation_date")->nullable();
            $table->time("cancellation_time")->nullable();
            $table->foreign("booking_id")->references("id")->on("object_bookings")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('cancelled_bookings');
    }
}
