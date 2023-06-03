<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($main_id = 1, $maxLevel = 1)
    {
        $arFaker = Factory::create("ar_JO");
        $enFaker = Factory::create("en_US");
        $level = 1;
        while ($level <= $maxLevel){
            $count = $enFaker->numberBetween(1,10);
            while ($count > 0){
                $category = new SubCategory();
                $category->name_en = $enFaker->jobTitle();
                $category->name_ar = $arFaker->jobTitle();
                $category->main_id = $main_id;
                $category->level = $level;
                $category->parent_id = $level > 1 ? SubCategory::where([["level" , $level - 1], ["main_id", $main_id]])->get()->random()->id : null;
                $category->save();
                $count--;
            }
            $level++;
        }

    }
}
