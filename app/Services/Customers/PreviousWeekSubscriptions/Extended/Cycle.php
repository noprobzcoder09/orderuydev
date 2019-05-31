<?php

namespace App\Services\Customers\PreviousWeekSubscriptions\Extended;

use App\Services\Manageplan\Cycle as CycleParent;

Class Cycle extends CycleParent
{   
    private $id;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
