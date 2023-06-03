<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermission([
            ["en" => "View Dashboard", "ar" => "عرض لوحة العدادت"],
            ["en" => "View Users", "ar" => "عرض المستخدمين"],
            ["en" => "View Admin Control", "ar" => "عرض  الادارة"],
//            ["en" => "Update And Control Admin", "ar" => "التحكم الادارية"],
//            ["en" => "Update permission Uesrs", "ar" => "تعديل صلاحيات المستخدمين"],
//            ["en" => "View And Control Uesrs Requests Join", "ar" => "عرض والتحكم في طلبات انضمام المستخدمين"],

            ["en" => "View Messages", "ar" => "عرض الرسائل"],
//            ["en" => "Update And Control Messages" , "ar" => "تعديل والتحكم بالرسائل"],

            ["en" => "View Ads", "ar" => "عرض الاعلانات"],
//            ["en" => "Update And Control Ads" , "ar" => "تعديل والتحكم بالاعلانات"],
            
            ["en" => "View Promos Code", "ar" => "عرض  كود العروض"],
//            ["en" => "Update And Control Promo Code" , "ar" => "تعديل والتحكم بالاكواد التروجية"],
                
            ["en" => "View Consultations", "ar" => "عرض الاستشارات"],
//            ["en" => "Update And Control Consultations" , "ar" => "تعديل والتحكم بالاستشارات "],

            ["en" => "View sliders","ar" => "عرض أشرطة التمرير"],
//            ["en" => "Update Sliders","ar" => "تعديل أشرطةالتمرير"],

            ["en" => "View notifications","ar" => "عرض الاشعارات"],
//            ["en" => "Update And Control notifications","ar" => "تعديل والتحكم الاشعارات"],

            ["en" => "view Social Media","ar" => "إعدادات التحكم في وسائل التواصل الاجتماعي"],
//            ["en" => "Admin Control","ar" => "التحكم المدير"],
            ["en" => "Welcome Message","ar" => "الرسال الترحيبية"],

            ["en" => "View App Settings","ar" => "التحكم بالتطبيق"],

            ["en" => "social media","ar" => "وسائل التواصل الاجتماعي"],
            ["en" => "Welcome Message","ar" => "الرسال الترحيبية"],

            ["en" => "admin role","ar" => "ادارة الصلاحيات"],

            ["en" => "View Specialization","ar" => "عرض الاختصاصات"],
            ["en" => "View Doctor","ar" => "عرض الأطباء"],
            ["en" => "View Notification Reminder","ar" => "عرض تذكير الاشعارات"],



        ]);

    }

    public function createPermission($name ){

        if(!is_array($name)){
            $data["name"] = $name;
            $data["slug"] = \Illuminate\Support\Str::lower(str_replace(" ","-",$name));
        }else{
            $counter = 0;
            foreach ($name as $val){
                $data[$counter]["name"] = $val["en"];
                $data[$counter]["name_ar"] = $val["ar"];
                $data[$counter]["slug"] = \Illuminate\Support\Str::lower(str_replace(" ","-",$val["en"]));
                $counter++;
            }
        }

        DB::table('admin_permissions')->insert($data);
    }
}
