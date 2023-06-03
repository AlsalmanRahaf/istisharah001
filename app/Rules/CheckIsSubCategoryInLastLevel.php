<?php

namespace App\Rules;

use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Contracts\Validation\Rule;

class CheckIsSubCategoryInLastLevel implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $mainCategory;
    protected $subCategory;
    protected $messageText;

    public function __construct(MainCategory $mainCategory)
    {
        $this->mainCategory = $mainCategory;
        $this->messageText = __('The Sub Category Must Be In Last Level');
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
         $maxLevel = $this->mainCategory->limit_levels_of_sub_categories;
         $subCategory = SubCategory::find($value);
         if($subCategory){
             if($subCategory->level == $maxLevel)
                 return true;
         }else
             $this->messageText = __('The Sub Category Is Not Exist');
        return false;
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
