<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminRolesSeeder::class,
            AdminSeeder::class,
            AppSettingSeeder::class,
            ObjectTypesSeeder::class,
            ObjectSeeder::class,
            TimeSlotTypesSeeder::class,
          //  ObjectDetailsSeeder::class,
            ObjectWeekDaysSeeder::class,
            TimeSlotSeeder::class,
            BookingMessagesSeeder::class,
           // MainButton::class,
           // MainCategorySeeder::class,
          //  PaymentMethodSeeder::class,
            PermissionsSeeder::class,
         //   SectionSeeder::class,
        //    SubCategorySeeder::class,
        //    UserSeeder::class
        ]);
           // $this->call([AppSettingSeeder::class]);
    }
}
