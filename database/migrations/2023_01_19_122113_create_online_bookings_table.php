<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("booking_id");
            $table->string("zoom_url");
            $table->string("meeting_id");
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
        Schema::dropIfExists('online_bookings');
    }
}
