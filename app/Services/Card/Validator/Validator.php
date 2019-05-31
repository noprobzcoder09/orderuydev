<?php

namespace App\Services\Card\Validator;

use  App\Services\Card\Validator\AbstractValidator;
use  App\Services\Card\Validator\Rules;
use  App\Services\Card\Validator\DB;

use App\Services\Card\Contracts\Validator as ValidatorInterface;

Class Validator extends AbstractValidator
{   
    public function __construct(ValidatorInterface $instance)
    {
        $this->instance = $instance;
    }

    protected function load()
    {
        $this->instance->validator();
        if (!$this->instance->success()) {
            throw new \Exception($this->instance->message(), 1);
            
        }
    }

    public function validate()
    {
        $this->load();
    }


}
