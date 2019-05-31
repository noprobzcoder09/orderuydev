<?php

namespace App\Services\Customers\PreviousWeekSubscriptions\Extended;

use App\Services\Orders\Factory as OrderFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\ProductPlanRepository;

use \App\Services\Manageplan\Contracts\Order as OrderContract;
use \App\Services\Manageplan\Order as OrderParent;

Class Order extends OrderParent implements OrderContract
{   

    public function __construct(string $sessionIdentifier)
    {
        $this->order = new OrderFactory;
        $this->order = $this->order->session(new SessionStorage($sessionIdentifier));
        $this->repo = new ProductPlanRepository;
    }

}
