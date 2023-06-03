<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_consultations', function (Blueprint $table) {
            $table->id();
            $table->String("consultation_name_en");
            $table->String("consultation_name_ar");
            $table->unsignedBigInteger("consultant_id");
            $table->boolean("status");
            $table->foreign("consultant_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('custom_consultations');
    }
}
