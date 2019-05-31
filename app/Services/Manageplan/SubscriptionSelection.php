<?php

namespace App\Services\Manageplan;

use App\Repository\SubscriptionSelectionsRepository;

Class SubscriptionSelection
{   
    private $id;

    public function __construct()
    {
        $this->repo = new SubscriptionSelectionsRepository;
    }

    public function store()
    {        
        $this->validate($this->get());
        
        $this->repo->store($this->get());    

        if (empty($this->repo->id)) {
            throw new Exception(sprintf(__('crud.failedToCreate'),'subscription selection'), 1);
        }

        $this->setId($this->repo->id);    
    }

    public function update()
    {        
        $this->validate($this->get());
        
        $this->repo->update($this->getId(), $this->get());    

        if (empty($this->repo->id)) {
            throw new Exception(sprintf(__('crud.failedToUpdate'),'subscription selection'), 1);
        }

        $this->setId($this->repo->id);    
    }

    public function updateInvoice(int $primaryId, string  $invoiceId)
    {
        $this->repo->updateInvoice($primaryId, $invoiceId);
    }   

    public function updateCurrentSubscriptionWeekCycleId(int $userId, int  $cycleId, int $deliveryZoneID)
    {
        $this->repo->updateCurrentSubscriptionWeekCycleId($userId, $cycleId, $deliveryZoneID);    
    }

    public function isExistCycle(int $cycleId, int $subscriptionId)
    {
        return $this->repo->iHaveIt($cycleId, $subscriptionId);
    }

    public function get()
    {        
        return $this->data;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function set(int $userId, int $subscriptionId, int $cycleId, string $selections, $deliveryZoneID, int $discountId, $status)
    {
        $this->data = [
            'subscription_id' => $subscriptionId,
            'user_id' => $userId,
            'cycle_id' => $cycleId,
            'menu_selections' => $selections,
            'cycle_subscription_status' => $status,
            'delivery_zone_id' => $deliveryZoneID,
            'discount_id' => $discountId,
        ];
    }

    private function rules()
    {
        return [
            'subscription_id' => 'required',
            'user_id' => 'required',
            'cycle_id' => 'required',
            'menu_selections' => 'required',
            'cycle_subscription_status' => 'required',
            'delivery_zone_id' => 'required'
        ];
    }

    protected function validate($data)
    {
        $validator = new \App\Services\Validator;

        $validator->validate($data, $this->rules());

        if (!$validator->isValid) {
            throw new \Exception (
                $validator->filterError($validator->messages),
                __('codes.rulesInvalid')
            );
        }
    }

}
