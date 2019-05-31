<?php

namespace App\Services\Coupons\Model;

use App\Models\Coupons;

Class Data
{    
    public function __construct(string $code = '')
    {
        $this->model = new Coupons;
        if (!empty($code)) {
            $this->set($code);
        }
    }

    public function set(string $code)
    {
        $this->data = $this->model->where('coupon_code', $code)->first();
    }

    public function find()
    {
       return $this->data->id ?? false;
    }

    public function getCode()
    {
       return $this->data->coupon_code ?? '';
    }

    public function getId()
    {
       return $this->data->id ?? 0;
    }

    public function getMaxUsed()
    {   
        return $this->data->max_uses ?? 0;
    }

    public function getNumberUsed()
    {   
        return $this->data->number_used ?? 0;
    }

    public function getMinOrder()
    {   
        return $this->data->min_order ?? 0;
    }

    public function getExpiredDate()
    {   
        return $this->data->expiry_date ?? '';
    }

    public function getType()
    {
       return $this->data->discount_type ?? '';
    }

    public function getDiscount()
    {
       return $this->data->discount_value ?? 0;
    }

    public function getProducts()
    {
        return !empty($this->data->products) ? json_decode($this->data->products) : [];
    }

    public function getUsers()
    {   
        return !empty($this->data->user) ? json_decode($this->data->user) : [];
    }

    public function isSolo()
    {
        return $this->data->solo ?? false;
    }

    public function isOnetime()
    {
        return $this->data->onetime ?? false;
    }

    public function isRecur()
    {
        return $this->data->recur ?? false;
    }

    public function isUsed()
    {
        return $this->data->used ?? false;
    }

    public function setUsed(string $code)
    {
        $this->set($code);
        $this->model->where('id', $this->getId())
        ->update([
            'number_used' => $this->getNumberUsed() + 1
        ]);
    }
}
