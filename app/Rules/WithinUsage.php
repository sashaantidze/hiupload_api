<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WithinUsage implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (($value + auth()->user()->usage()) > auth()->user()->plan->storage) {
            return false;
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
        return 'Seems like the file/files you are trying to upload is too large, please make sure you have enough space or upgrade your plan';
    }
}
