<?php

namespace App\Services\Manageplan;

use App\Repository\ZTRepository;

Class DeliveryZoneTiming
{   
    private $id;

    private $data;

    public function __construct()
    {
        $this->repo = new ZTRepository;
    }

    public function set(int $id)
    {
        $this->data = $this->repo->get($id);
    }

    public function getDeliveryTimingId()
    {
        return $this->data->delivery_timings_id ?? 0;
    }

    public function getDeliveryZoneId()
    {
        return $this->data->delivery_zone_id ?? 0;
    }

    public function get()
    {
        return $this->data;
    }
}
