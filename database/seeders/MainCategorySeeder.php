<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use App\Models\Section;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MainCategorySeeder extends Seeder
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
            $category = new MainCategory();
            $category->name_en = $enFaker->jobTitle();
            $category->name_ar = $arFaker->jobTitle();
            $category->section_id = Section::all()->random()->id;
            $category->group_type = $enFaker->randomElement(["eo", "ml", "wk", "om", "cc", "cr"]);
            $levelSub = $enFaker->numberBetween(1,6);
            $category->status = 1;
            $category->limit_levels_of_sub_categories = $levelSub;
            $category->save();
            $count--;
            $this->call([SubCategorySeeder::class],false, ["main_id" => $category->id, "maxLevel" => $levelSub]);


        }
    }
}
