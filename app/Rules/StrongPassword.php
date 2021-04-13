<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class StrongPassword implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen($value) >= 8                                      // more than 8 characters
            && Str::lower($value) != $value                             // contains uppercase
            && ((bool) preg_match('/[0-9]/', $value))            // contains digits
            && ((bool) preg_match('/[^A-Za-z0-9]/', $value));    // contains special characters

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "password must contain at least one uppercase, an special character, and a digit";
    }
}
