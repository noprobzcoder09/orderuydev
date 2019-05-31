<?php

namespace App\Services\Card\Contracts;

Interface Gateway
{   
    public function store(array $data);
}
