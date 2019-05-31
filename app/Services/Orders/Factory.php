<?php

namespace App\Services\Orders;

use App\Services\Orders\AbstractOrderFactory;

Class Factory implements AbstractOrderFactory
{     
    public function session(\App\Services\Session\AdapterInterface $session)
    {
    	return new \App\Services\Orders\Session($session);
    }

    public function db()
    {
    	return new \App\Services\Orders\Db;
    }
}
