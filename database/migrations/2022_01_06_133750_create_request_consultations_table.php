<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('request_consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("room_id");
            $table->integer("type_of_request")->comment("1-normal 2-Pharmacist 3-nutrition 4-diabetes")->default(1);
            $table->integer("status")->default(1)->comment("1-waiting and not readed 2-accepted and readed 3 -follow up 4-rejected 5-completed 6-vip");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("room_id")->references("id")->on("rooms")->cascadeOnUpdate()->cascadeOnDelete();

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
        Schema::dropIfExists('request_consultations');
    }
}
