<?php

namespace App\Services\Card\Contracts;

Interface User
{   
    public function getContactId();
    public function getCardId();
    public function storeCardId($id, $last4);
}
