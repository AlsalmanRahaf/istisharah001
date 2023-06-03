<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddOnsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_ons_options', function (Blueprint $table) {
            $table->id();
            $table->string("name_en");
            $table->string("name_ar");
            $table->decimal("price", 8,2);
            $table->bigInteger("add_on_id")->unsigned()->index();

            $table->foreign("add_on_id")->references("id")->on("add_ons")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('add_ons_options');
    }
}
