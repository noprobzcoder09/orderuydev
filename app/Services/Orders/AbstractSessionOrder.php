<?php

namespace App\Services\Orders;

use App\Services\Orders\AbstractAuthCart;

Interface AbstractSessionOrder
{     
    public function store(array $meals = []);
    public function delete(int $planId);
    public function get();
    public function getTotal();
    public function getPlanId();
}

