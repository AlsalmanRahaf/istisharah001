<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AfterTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $after;
    protected $afterString;
    public function __construct($after = "now")
    {
        $this->afterString = $after;
        $this->initizeAfter($after);
    }

    public function initizeAfter($after){
        if($after == "now"){
            $this->after = time();
        }else{
            $this->after = strtotime($after);
        }
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
       $time = strtotime($value);
       if($time > $this->after)
           return true;
       else
           return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must be after ' . $this->afterString);
    }
}
