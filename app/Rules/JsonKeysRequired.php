<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Nette\Utils\Json;

class JsonKeysRequired implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $keys;
    protected $errorsKey;
    public function __construct(Array $keys)
    {
        $this->keys = $keys;
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
        $address = json_decode($value, true);
        foreach ($this->keys as $key){
            if(!isset($address[$key]) || empty($address[$key])){
                $this->errorsKey = $key;
                return false;
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
        return "The {$this->errorsKey} is required";
    }
}
