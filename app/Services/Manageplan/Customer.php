<?php

namespace App\Services\Manageplan;

use App\Repository\CustomerRepository;

Class Customer
{   
    private $id;

    private $data;

    public function __construct()
    {
        $this->repo = new CustomerRepository;
    }

    public function updateDeliveryZoneTimingId(int $userId, int $deliveryZoneTimingId)
    {
        $this->repo->updateDeliveryZoneTimingId(
            $userId, $deliveryZoneTimingId
        );
    }
}
