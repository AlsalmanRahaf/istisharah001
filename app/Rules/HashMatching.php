<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class HashMatching implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $hash;
    public function __construct($hash)
    {
        $this->hash = $hash;
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
        return Hash::check($value, $this->hash);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not match';
    }
}
