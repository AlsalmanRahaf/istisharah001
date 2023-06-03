<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersDashboards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_dashboards', function (Blueprint $table) {
            $table->id();
            $table->string("full_name");
            $table->string("username")->unique();
            $table->string("email")->unique();
            $table->string("email_verified_at");
            $table->string("password");
            $table->string("active")->nullable();
            $table->string("profile_photo");
            $table->text("token");
            $table->unsignedBigInteger("role_id");
            $table->integer("is_admin")->nullable();
            $table->string("remember_token")->nullable();
            $table->timestamps();

            $table->foreign("role_id")->references("id")->on("admin_roles")->cascadeOnDelete()->cascadeOnUpdate();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_dashboards');
    }
}
