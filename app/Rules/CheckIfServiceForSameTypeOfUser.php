<?php

namespace App\Rules;

use App\Models\Service;
use Illuminate\Contracts\Validation\Rule;

class CheckIfServiceForSameTypeOfUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $user;
    protected $messageText;

    public function __construct($user)
    {
        $this->user = $user;
        $this->messageText = __('The Service Can\'t Be Add To User');

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
        $service = Service::find($value);
        if($service){
            $groupType = $service->category->main->group_type;
            $condition = $this->user->type === $groupType;
            if($this->user->type === "wk" || $this->user->type === "cr"){
                if($this->user->profile && $this->user->profile->with_maintenance){
                    $condition = $this->user->type === $groupType || $groupType == "mc";
                }
            }
            return $condition;
        }else
            $this->messageText = __('The Service Is Not Exists');

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messageText;
    }
}
