<?php

namespace App\Services\Manageplan;

use App\Repository\SubscriptionRepository;

Class Subscription
{   
    private $id;

    public function __construct()
    {
        $this->repo = new SubscriptionRepository;
    }

    public function store()
    {        
        $this->validate($this->get());

        $this->repo->store($this->get());    

        if (empty($this->repo->id)) {
            throw new Exception(sprintf(__('crud.failedToCreate'),'subscription'), 1);
        }
        
        $this->setId($this->repo->id);    
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

    public function set(int $userId, int $plansId, float $price, string $status = 'active')
    {
        $this->data = [
            'user_id' => $userId, 
            'meal_plans_id' => $plansId,
            'price' => $price,
            'status' => $status
        ];
    }

    private function rules()
    {
        return [
            'user_id' => 'required', 
            'meal_plans_id' => 'required', 
            'price' => 'required'
        ];
    }

    public function validate($data)
    {
        $validator = new \App\Services\Validator;

        $validator->validate($data, $this->rules());

        if (!$validator->isValid) {
            throw new \Exception(
                $validator->filterError($validator->messages),
                __('codes.rulesInvalid')
            );
        }
    }

}
