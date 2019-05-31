<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use App\Services\Coupons\Model\Data;
use App\Services\Manageplan\Contracts\Order as OrderContract;

Class Product extends AbstractValidator
{    
    private $order;

    public function __construct(string $code, OrderContract $order)
    {
        $this->data = new Data($code);
        $this->order = $order;
        $this->message = __('coupon.products');
        
        $this->load();  
    }

    public function load()
    {
        if (count($this->data->getProducts()) == 0) {
            return $this->valid = true;
        }
        foreach($this->order->get() as $key => $row) 
        {
            foreach($this->data->getProducts() as $p) {
                if ($p == $key) {
                    $this->valid = true;
                    return true;
                }
            }
        }
    }

}
