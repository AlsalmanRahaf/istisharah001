<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayKeyExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected array $array;
    protected $errorMessage;
    public function __construct(array $array)
    {
        $this->array = $array;
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
        $this->errorMessage = "The " . $attribute . " is not valid";
        return isset($this->array[$value]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __($this->errorMessage);
    }
}
