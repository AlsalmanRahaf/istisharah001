<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateButtonSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('button_settings', function (Blueprint $table) {
            $table->id();
            $table->integer("type")->comment("1-consultation button 2-chat history button");
            $table->boolean("status")->default(1)->comment("1 -active 2- inactive ");
            $table->string("title_en");
            $table->string("title_ar");
            $table->string("description_en");
            $table->string("description_ar");
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
        Schema::dropIfExists('button_settings');
    }
}
