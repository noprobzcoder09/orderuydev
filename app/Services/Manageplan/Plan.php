<?php

namespace App\Services\Manageplan;

use App\Services\Orders\Factory as OrderFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\ProductPlanRepository;

Class Plan
{   
    private $id;

    public function __construct()
    {
        $this->repo = new ProductPlanRepository;
    }

    public function setId(int $id)
    {   
        $this->id = $id;
        $this->repo->setId($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->repo->getPrice();
    }

    public function isVegetarian()
    {
        return $this->repo->isVegetarian();
    }
}
