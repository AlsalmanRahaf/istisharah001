<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($count = 1)
    {
        $arFaker = Factory::create("ar_JO");
        $enFaker = Factory::create("en_US");

        while ($count > 0){
            $gender = $arFaker->randomElement([1,2]);
            $user = new User();
            $user->full_name = $gender == 1 ? $arFaker->firstNameMale : $arFaker->firstNameFemale;
            $user->email = $arFaker->unique->email;
            $user->phone_number = $arFaker->unique->phoneNumber;
            $user->status = 1;
            $user->firebase_uid = Str::random(20);
            $user->gender = $gender;
            $user->birth_date = $enFaker->date("Y-m-d", "2004-1-1");
            $user->type = $arFaker->randomElement(["wk","cc","cr","om","eo","cl","sp"]);
            $user->save();
            $count--;
        }
    }
}
