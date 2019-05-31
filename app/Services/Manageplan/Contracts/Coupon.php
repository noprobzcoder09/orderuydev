<?php

namespace App\Services\Manageplan\Contracts;

Interface Coupon
{   
    public function store(string $code);
    public function get();
    public function delete(string $code);
    public function destroy();
}
