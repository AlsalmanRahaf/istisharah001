<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("object_id");
            $table->unsignedBigInteger("time_slot_type_id");
            $table->string("description")->nullable();
            $table->foreign("object_id")->references("id")->on("objects")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('object_details');
    }
}
