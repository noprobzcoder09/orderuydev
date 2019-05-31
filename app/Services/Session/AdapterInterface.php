<?php

namespace App\Services\Session;

Interface AdapterInterface 
{     
    public function store(array $data);
    public function get();
    public function iHaveData();
}
