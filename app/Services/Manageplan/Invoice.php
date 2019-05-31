<?php

namespace App\Services\Manageplan;

use App\Services\Orders\Factory as OrderFactory;
use App\Services\Session\Session as SessionStorage;
use App\Repository\SubscriptionInvoiceRepository;

Class Invoice
{   
    private $id;

    public function __construct()
    {
        $this->repo = new SubscriptionInvoiceRepository;
    }

    public function setId(int $id)
    {
       $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function store(array $data)
    {
        $this->repo->store($data);

        $this->setId($this->repo->getId());
    }
}
