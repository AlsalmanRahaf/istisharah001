<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("consultation_id");
            $table->string("consultant_type")->default("c");
            $table->integer("action")->comment("1-read 2-follow up 3-reject 4-complete ");
            $table->foreign("consultation_id")->references("id")->on("consultations")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('consultation_logs');
    }
}
