<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIsValidTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $error_message;
    public function __construct()
    {
        //
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
        $this->error_message = "The {$attribute} is not valid";
        $dateObj = \DateTime::createFromFormat('H:i', $value);
        if($dateObj && $dateObj->format('H:i') == $value)
            return true;
        else{
            $dateObj = \DateTime::createFromFormat('H:i:s', $value);
            if($dateObj && $dateObj->format('H:i:s') == $value)
                return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
