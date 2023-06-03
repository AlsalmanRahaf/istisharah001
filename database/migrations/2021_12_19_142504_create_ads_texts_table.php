<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_texts', function (Blueprint $table) {
            $table->id();
            $table->String("Data");
            $table->String("background_color")->default("#FFFFFF");
            $table->unsignedBigInteger("ads_id");
            $table->foreign("ads_id")->references("id")->on("ads")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('ads_texts');
    }
}
