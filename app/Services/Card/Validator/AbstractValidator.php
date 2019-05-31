<?php

namespace App\Services\Card\Validator;

Abstract Class AbstractValidator
{   
    protected abstract function load();

    public abstract function validate();
}
