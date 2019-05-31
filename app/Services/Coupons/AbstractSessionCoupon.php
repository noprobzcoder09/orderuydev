<?php

namespace App\Services\Coupons;

Interface AbstractSessionCoupon
{     
 	public function store(array $data);
    public function delete(string $code);
    public function get();
}
