<?php

namespace App\Services\Manageplan\Contracts;

Interface Request
{   
    public function getPromoCode();
    public function getPlanId();
    public function deliveryZoneTimingId();
    public function deliveryZoneId();
}
