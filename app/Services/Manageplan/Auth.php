<?php

namespace App\Services\Manageplan;

Class Auth implements \App\Services\Manageplan\Contracts\Auth
{   
    private $id;
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
