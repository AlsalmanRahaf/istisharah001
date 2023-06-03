<?php

namespace Database\Seeders;

use App\Models\Section;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    protected $count;
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
            $section = new Section();
            $section->name_en = $enFaker->jobTitle();
            $section->name_ar = $arFaker->title();
            $section->is_categories_view_in_home_page = $enFaker->randomElement([1,0]);
            $section->save();
            $count--;
        }
    }
}
