<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Custom implements Rule
{   
    public $value;
    public $calllback;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($calllback)
    {
        $this->calllback = $calllback;
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
        $calllback = $this->calllback;
        $this->value = $value;

        return $calllback($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The ".(is_array($this->value) ? ':attribute' : $this->value)." is already taken.";
    }
}
