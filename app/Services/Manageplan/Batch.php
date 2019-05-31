<?php

namespace App\Services\Manageplan;

Class Batch implements \App\Services\Manageplan\Contracts\Batch
{   
    private $id;
    public function set($id)
    {
        $this->id = $id;
    }

    public function get()
    {
        return $this->id;
    }
}
