<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("item_id")->unsigned()->index();
            $table->bigInteger("branch_id")->unsigned()->index();

            $table->foreign("branch_id")->references("id")->on("branches")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("item_id")->references("id")->on("items")->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('item_branches');
    }
}
