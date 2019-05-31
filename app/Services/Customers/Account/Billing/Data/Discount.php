<?php

namespace App\Services\Customers\Account\Billing\Data;

use App\Models\SubscriptionsDiscounts;

Class Discount
{  
    public function __construct()
    {
        $this->model = new SubscriptionsDiscounts;
    }

    public function get(int $id)
    {
        return $this->model->where([
            'id' => $id
        ]);
    }
}
