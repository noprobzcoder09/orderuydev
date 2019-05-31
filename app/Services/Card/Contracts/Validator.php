<?php

namespace App\Services\Card\Contracts;

Interface Validator
{   
    public function validator();
    public function success();
    public function message();
}
