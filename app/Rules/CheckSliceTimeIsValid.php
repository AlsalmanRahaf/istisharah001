<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckSliceTimeIsValid implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $from, $to;
    public function __construct($from, $to)
    {
        $from = explode(":", $from);
        $to = explode(":", $to);
        $this->from = floatval($from[0] . "." . $from[1]);
        if($to[0] == "23" && $to[1] == "59")
            $this->to = 24.00;
        else
            $this->to = floatval($to[0] . "." . $to[1]);;
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
        $timeLength = $this->to - $this->from;

        if(fmod($timeLength , floatval($value / 60)) != 0)
            return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The slice time must be valid');
    }
}
