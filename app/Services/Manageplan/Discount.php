<?php

namespace App\Services\Manageplan;

use App\Repository\SubscriptionDiscountsRepository;

Class Discount implements \App\Services\Manageplan\Contracts\Discount
{   
    private $id = 0;

    public function __construct()
    {
        $this->repo = new SubscriptionDiscountsRepository;
    }

    public function store()
    {        
        $this->validate($this->get());

        $this->repo->storeAsArray($this->get());    

        if (empty($this->repo->id)) {
            throw new Exception(sprintf(__('crud.failedToCreate'),'Discount'), 1);
        }

        $this->setId($this->repo->id);    
    }

    public function get()
    {        
        return $this->data;
    }

    private function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTotal(float $total)
    {
        $this->total = $total;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotalRecur(float $total)
    {
        $this->totalRecur = $total;
    }

    public function getTotalRecur(): float
    {
        return $this->totalRecur;
    }

    public function set(string $meta, float $discount, float $recur)
    {
        $this->data = [
            'meta_data' => $meta, 
            'total_discount' => $discount,
            'total_recur_discount' => $recur
        ];
    }

    public function updateDiscountNumberSubscriptions(int $discountId, int $numberOfSubscriptions)
    {
        $this->repo->updateDiscountNumberSubscriptions(
            $discountId,
            $numberOfSubscriptions
        );
    }

    private function rules()
    {
        return [
            'meta_data' => 'required',
            'total_discount'  => 'required',
        ];
    }

    private function validate($data)
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
