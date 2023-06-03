<?php
namespace App\Traits;
use App\Exports\ExeclExport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

trait Helper{
    
    public function setPageMessage($msg, $status=1, $key="page-message"){
        $status = $status == 1 ? "success" : ($status == 0 ? "danger" : "warning");
        \session()->flash($key, ["status" => $status, "message" => $msg]);
    }


    public function getUserType($type){
        switch ($type) {
            case 'c':
                $type = "Consultant";
                break;
            case 'u':
                $type = "user";
                break;
            case 'cph':
                $type = "Pharmacist";
                break;
            case 'cn':
                $type = "nutrition";
                break;
            case 'cd':
                $type = "diabetes";
                break;
        }
        return $type;
    }

    public function sendRequest($method,$body,$url){
            $response = Http::asForm()->$method($url, $body);
            return $response;
    }

    public function upload($file, $directoryPath){
        $imageName = time() . rand(0,100000000000) * 40 . "." . $file->getClientOriginalExtension();
        $file->move($directoryPath, $imageName);
        return $imageName;
    }

    public function downloadExcelFile(array $columnsWellRetrived, $model , array $headings = [], $fileName = "excel.xlsx", $formats = []){
        $data = [];
        if($model instanceof \Illuminate\Support\Collection){
            $i=0;
            foreach ($model as $element){
                foreach ($columnsWellRetrived as $key => $column){
                    if($key === "custom"){
                        $data[$i][$column["name"]] = (string) $column["values"][$element->{$column["name"]}];
                    }else{
                        $data[$i][$column] = (string) $element->{$column};
                    }
                }
                $i++;
            }
        }else{
            foreach ($columnsWellRetrived as $key => $column){
                if($key === "custom"){
                    $data[$column["name"]] = (string) $column["values"][$model->$column["name"]];
                }else{
                    $data[$column] = (string) $model->{$column};
                }
            }
        }

       return Excel::download(new ExeclExport($data, $headings , $formats), $fileName);
    }
}
