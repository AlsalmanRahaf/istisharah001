<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingNotificationTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_notification_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("booking_id");
            $table->date("notification_date");
            $table->time("notification_time");
            $table->boolean("is_sent")->default(0);
            $table->date("sent_date")->nullable();
            $table->time("sent_time")->nullable();
            $table->unsignedBigInteger("notification_id")->nullable();
            $table->foreign("booking_id")->references("id")->on("object_bookings")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("notification_id")->references("id")->on("notifications")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('booking_notification_times');
    }
}
