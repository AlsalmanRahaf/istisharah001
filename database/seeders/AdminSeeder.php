<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'username' => "admin",
            'email' => 'admin@admin.com',
            'full_name' => "admin",
            'password' => Hash::make('admin'),
            'is_super_admin' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
            "status" => 1,
            "role_id"=> 1,
        ]);
    }
}
