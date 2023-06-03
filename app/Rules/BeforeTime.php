<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BeforeTime implements Rule
{
    protected $before;
    protected $beforeString;
    public function __construct($before = "now")
    {
        $this->beforeString = $before;
        $this->initizebefore($before);
    }

    public function initizebefore($before){
        if($before == "now"){
            $this->before = time();
        }else{
            $this->before = strtotime($before);
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
        if($time < $this->before)
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
        return __('The :attribute must be before ' . $this->beforeString);
    }
}
