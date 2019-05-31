<?php

namespace App\Services\Orders;

use Request;

use Session;

Interface AbstractOrderFactory
{     
    
    public function session(\App\Services\Session\AdapterInterface $session);
    public function db();    

}
