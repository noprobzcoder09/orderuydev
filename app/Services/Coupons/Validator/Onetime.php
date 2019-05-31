<?php

namespace App\Services\Coupons\Validator;

use App\Services\Coupons\Validator\AbstractValidator;
use App\Services\Coupons\Model\Data;
use App\Models\SubscriptionsDiscounts;
use App\Models\SubscriptionsSelections;

Class Onetime extends AbstractValidator
{    
    private $userId;
    private $code;

    public function __construct(string $code, int $userId)
    {   
        $this->data = new Data($code);
        $this->code = $code;
        $this->userId = $userId;
        $this->message = sprintf(__('coupon.onetime'), $code);
        
        $this->load();
    }

    /**
    *
    * The return should be true if not used
    * Otherwise false
    *
    */
    public function load()
    {   
        if (!$this->data->isOnetime()) {
            return $this->valid = true;
        }

        $discounts = [];
        foreach((new SubscriptionsSelections)->where('user_id', $this->userId)->get() as $s) 
        {
            foreach((new SubscriptionsDiscounts)->where('id', $s->discount_id)->get() as $d) 
            {
                $meta = json_decode($d->meta_data);
                foreach($meta as $m) 
                {
                    array_push($discounts, strtolower($m->code));
                }
            }
        }
        
        $this->valid = !in_array(strtolower($this->code), $discounts);
    }

}
