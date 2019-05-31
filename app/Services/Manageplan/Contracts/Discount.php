<?php

namespace App\Services\Manageplan\Contracts;

Interface Discount
{   
    public function store();
    public function set(string $meta, float $discount, float $recur);
    public function setTotal(float $total);
    public function getTotal();
    public function setTotalRecur(float $total);
    public function getTotalRecur();
}
