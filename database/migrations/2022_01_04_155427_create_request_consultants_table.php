<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_consultants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->integer("status")->default(1)->comment("1-waiting 2-accepted 3-rejected 4-Accept Other");
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('request_consultants');
    }
}
