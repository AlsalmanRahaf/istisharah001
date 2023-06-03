<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->String("full_name")->nullable();
            $table->String("phone_number")->unique()->nullable();
            $table->integer("age" )->nullable();
            $table->String("firebase_uid")->unique()->nullable();
            $table->String("diseases")->nullable();
            $table->boolean("status")->default(1);
            $table->String("country")->nullable();
            $table->String("description")->nullable();
            $table->date("date_of_birth")->nullable();
            $table->string("country_code")->nullable();
            $table->text("device_token")->nullable();
            $table->String("type",3)->default('u')->comment("u->user,c->Consultant,a->admin cph-Consultant_Pharmacist cn-Consultant_nutrition cd-Consultant_diabetes");
            $table->boolean("switch_status")->default(0);
            $table->string("notification_lang", 20)->default("en");
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
        Schema::dropIfExists('users');
    }
}
