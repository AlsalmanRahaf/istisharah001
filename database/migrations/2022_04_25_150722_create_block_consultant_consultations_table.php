<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockConsultantConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_consultant_consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("consultation_id");
            $table->unsignedBigInteger("admin_id");
            $table->String("type")->comment("1-normal 2-referred consultation 3-other consultation");
            $table->foreign("admin_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('block_consultant_consultations');
    }
}
