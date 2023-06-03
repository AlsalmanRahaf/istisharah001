<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdminRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_permission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("role_id");
            $table->unsignedBigInteger("permission_id");
            $table->foreign("role_id")->references("id")->on("admin_roles")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("permission_id")->references("id")->on("admin_permissions")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role_permission');
    }
}
