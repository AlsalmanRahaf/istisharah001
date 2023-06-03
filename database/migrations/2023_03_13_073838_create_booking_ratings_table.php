<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("rated_by");
            $table->unsignedBigInteger("rated_doctor");
            $table->unsignedBigInteger("user_booking_id");
            $table->double("rating_value");
            $table->text("notes")->nullable();
            $table->foreign("rated_by")->references("id")->on("users")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("rated_doctor")->references("id")->on("doctors")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("user_booking_id")->references("id")->on("user_bookings")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('booking_ratings');
    }
}
