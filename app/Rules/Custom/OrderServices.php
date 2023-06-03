<?php

namespace App\Rules\Custom;

use App\Models\PropertySelection;
use App\Models\Service;
use App\Models\ServiceProperty;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OrderServices implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected Request $request;
    protected  $messages;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $serviceInfo = $value;
        $service =
        $service = Service::find((int)$serviceInfo["service_id"] ?? null);
        $rules = [
            "service_id"    => ["required", "numeric"],
            "properties"    => ["array"],
            "questions"    => ["array"]
        ];
        if($service && $service->properties->isNotEmpty())
            array_unshift($rules["properties"], "required");
        if($service && $service->questions->isNotEmpty())
            array_unshift($rules["questions"], "required");

        $valid = Validator::make($serviceInfo, $rules);
        if($valid->fails()){
            $this->messages = $valid->errors()->messages();
            return false;
        }

        if(!$service){
            $this->messages = __("the service is not valid.");
            return false;
        }



        if($service->settings->is_order_notes_required){
            if(!isset($serviceInfo["notes"]) || (!isset($serviceInfo["notes"]["image"]) && !isset($serviceInfo["notes"]["voice"]) && !isset($serviceInfo["notes"]["text"]))){
                $this->messages = __( "The notes is required.");
                return false;
            }
        }
        if(isset($serviceInfo["notes"])){
            $rules = [
                "image" => isset($serviceInfo["notes"]["image"])  ? ["image"] : [],
                "voice" => isset($serviceInfo["notes"]["voice"])  ? ["file", "mimes:mp3"] : [],
                "text" => isset($serviceInfo["notes"]["text"])  ? ["max:500"] : []
            ];
            $valid = Validator::make($serviceInfo["notes"], $rules);
            if($valid->fails()){
                $this->messages = $valid->errors()->messages();
                return false;
            }
        }


        if(isset($serviceInfo["properties"])){
            foreach ($serviceInfo["properties"] as $index => $property){
                $rules = [
                    "id"    => ["required", "numeric"],
                    "value"    => ["required"],
                ];

                $valid = Validator::make($property, $rules, [], ["value" => "property_value." . $index]);
                if($valid->fails()){
                    $this->messages = $valid->errors()->messages();
                    return false;
                }
                $serviceProperty = ServiceProperty::selectBuilder()->byService($serviceInfo["service_id"])->byId($property["id"])->first();
                if(!$serviceProperty){
                    $this->messages = __("the property is invalid.");
                    return false;
                }
                $rules = [];
                if($serviceProperty->type === "SW"){
                    if(!in_array($property["value"], ["0","1"])){
                        $this->messages = __("the property value is invalid.");
                        return false;
                    }
                }else{
                    if(!PropertySelection::where(["id" => (int)$property["value"], "property_id" => $property["id"]])->first()){
                        $this->messages = __("the property value is invalid.");
                        return false;
                    }
                }
            }
        }

        if(isset($serviceInfo["questions"])){
            foreach ($serviceInfo["questions"] as $question){
                $rules = [
                    "id"        => ["required", "numeric", "exists:questions,id"],
                    "answer"    => ["required", "in:0,1"],
                    "text"      => ["max:255"]
                ];
                $valid = Validator::make($question, $rules);
                if($valid->fails()){
                    $this->messages["questions"] = $valid->errors()->messages();
                    return false;
                }
            }
        }

    return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messages;
    }
}
