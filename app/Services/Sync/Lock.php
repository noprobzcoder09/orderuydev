<?php

namespace App\Services\Sync;

use App\Services\Sync\Data;
use App\Services\Sync\LockInterface;

Class Lock implements LockInterface
{       
    private $field;

    public function check(string $field)
    {
        $this->data = new Data;        

        if (count($this->data->getPendingByField($field)) > 0) {
            throw new \Exception("Updating for this field ". $field. " is locked due to pending/in-progress sync.", 1);
            
        }
    }

}

