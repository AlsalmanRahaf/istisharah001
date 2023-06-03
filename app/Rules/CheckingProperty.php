<?php

namespace App\Rules;

use App\Models\ServiceProperty;
use Illuminate\Contracts\Validation\Rule;
use Nette\Utils\Json;

class CheckingProperty implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $serviceId;
    public function __construct($serviceId)
    {
        $this->serviceId = $serviceId;
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
        $properties = $value;
        $properties = Json::decode($properties);
        foreach ($properties as $property){
            if(ServiceProperty::where([["id", $property->property_id], ["service_id", $this->serviceId]])->get()->isEmpty())
                return false;
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
        return 'The validation error message.';
    }
}
