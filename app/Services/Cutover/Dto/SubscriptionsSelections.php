<?php

namespace App\Services\Cutover\Dto;

Class SubscriptionsSelections
{   
   public function __construct(int $id, int $cycleId, string $status)
   {
        $this->id = $id;
        $this->cycleId = $cycleId;
        $this->status = $status;
   }

   public function getId()
   {
        return $this->id;
   }

   public function getCycleId()
   {
        return $this->cycleId;
   }

   public function getStatus()
   {
        return $this->status;
   }

}
