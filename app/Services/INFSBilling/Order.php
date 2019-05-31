<?php

namespace App\Services\INFSBilling;

Class Order extends \App\Services\InfusionSoftServices
{   

   public function __construct(int $contactId, \DateTime $dateTime, string $description = 'Customer Order', $api = null)
   {    
        if (is_null($api)) {
            parent::__construct();
        } else {
            $this->api = $api;
        }

        $id = $this->blankOrder($contactId, $description, $dateTime);


        if (empty($id)) {
            throw new Exception(sprintf(__('crud.failedToCreate'),'Infusionsoft order'), 1);
            
        }

        $this->setId($id);
   }

   private function setId($id)
   {
        $this->id = $id;
   }

   public function getId()
   {
        return $this->id;
   }
}
    
