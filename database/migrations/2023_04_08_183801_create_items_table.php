<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("name_en");
            $table->string("name_ar");
            $table->text("description_en");
            $table->text("description_ar");
            $table->decimal("price", 8,2);
            $table->decimal("tax", 4,2);
            $table->integer("quantity");
            $table->boolean("status")->default(1);
            $table->bigInteger("category_id")->unsigned()->index();

            $table->foreign("category_id")->references("id")->on("categories")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('items');
    }
}
