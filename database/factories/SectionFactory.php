<?php

namespace Database\Factories;

use App\Models\Section;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       // $arFaker = FakerFactory::create('ar_AA');
        return [
            'name_en' => 'dsadsa',
            'name_ar' => $this->faker->name(),
            'is_categories_view_in_home_page' => $this->faker->randomElement([0,1])
        ];
    }
}
