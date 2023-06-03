<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordPattern implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        $pattern = "/^(?=.*[A-Z])+(?=.*[a-z])+(?=.*[1-9])+.{8,20}$/";
        return (bool)preg_match($pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be contain at least one uppercase and lowercase charcter and number and the length must be between 8 and 20';
    }
}
