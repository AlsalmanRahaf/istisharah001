<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_permissions')->insert([
            'name' => "Admin Control",
            'slug' => 'admin-control',
        ]);

        DB::table('admin_role_permission')->insert([
            'role_id' => 1,
            'permission_id' => 1,
        ]);
    }
}
