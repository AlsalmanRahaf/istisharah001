<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainButton extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermission([
            ["type"=>1,"title_en" => "Application advisor", "title_ar" => "مستشار التطبيق","description_en"=>"to request a consultation ,click hear","description_ar"=>"لطلب استشارة ، انقر فوق سماع"],
            ["type"=>1,"title_en" => "Other Consultation", "title_ar" => "استشارة أخرى","description_en"=>"click hear","description_ar"=>"انقر فوق سماع"],
            ["type"=>2,"title_en" => "chat history", "title_ar" => "محادثات التطبيق","description_en"=>"","description_ar"=>""],
        ]);

    }

    public function createPermission($name ){


            $counter = 0;
            foreach ($name as $val){
                $data[$counter]["type"] = $val["type"];
                $data[$counter]["title_en"] = $val["title_en"];
                $data[$counter]["title_ar"] = $val["title_ar"];
                $data[$counter]["description_en"] = $val["description_en"];
                $data[$counter]["description_ar"] = $val["description_ar"];
                $data[$counter]["status"] = 1;
                $counter++;
            }
        DB::table('button_settings')->insert($data);
    }
}
