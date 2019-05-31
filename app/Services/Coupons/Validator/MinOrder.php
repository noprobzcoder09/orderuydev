<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use  App\Services\Coupons\Model\Data;
use \App\Services\Manageplan\Contracts\Order as OrderContract;

Class MinOrder extends AbstractValidator
{    
    private $orders;

    public function __construct(string $code, OrderContract $order)
    {
        $this->data = new Data($code);
        $this->order = $order;
        $this->message = sprintf(
            __('coupon.min_order'), 
            $code,__('config.currency').number_format($this->data->getMinOrder(), 2)
        );
        
        $this->load();
    }

    public function load()
    {   
        $total = 0;
        foreach($this->order->get() as $row)
        {   
            $row = is_array($row) ? (object)$row : $row;
            $total += ($row->quantity * $row->price);
        }
        
        if ($total > $this->data->getMinOrder()) {
            $this->valid = true;
        }
    }
}
