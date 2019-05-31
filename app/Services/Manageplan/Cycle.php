<?php

namespace App\Services\Manageplan;

use App\Repository\CycleRepository;

Class Cycle
{   
    private $id;

    private $data;

    public function __construct()
    {
        $this->repo = new CycleRepository;
    }

    public function set(int $timingId)
    {
        $this->data = $this->repo->getActiveByTimingId(
            $timingId
        );
    }

    public function getId()
    {
        return $this->data->id ?? 0;
    }

    public function getDefaultMenu(bool $vege)
    {
        return $vege ? $this->data->default_selections_veg ?? [] : $this->data->default_selections ?? [];
    }

    public function get()
    {
        return $this->data;
    }
}
