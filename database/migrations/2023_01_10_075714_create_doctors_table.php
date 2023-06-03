<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string("full_name");
            $table->string("phone_number");
            $table->string("email");
            $table->tinyInteger("has_zoom")->nullable();
            $table->unsignedBigInteger("specialization_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("object_id")->nullable();
            $table->string("payment_methods")->nullable();
            $table->text("description")->nullable();
            $table->foreign("specialization_id")->references("id")->on("specializations")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('doctors');
    }
}
