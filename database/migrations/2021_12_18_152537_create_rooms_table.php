<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->String("room_id")->default(1);
            $table->boolean("status")->default(1)->comment("1-active 2-inactive ");
            $table->integer("type")->comment("1-one-one 2-group")->default(1);
            $table->integer('standard')->comment("1 -normal 2-Vip ")->default(1);
            $table->integer('chat_type')->comment("1 -normal 2-consultant - user 3- admin - consultant ")->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
