<?php

namespace App\Services\Customers\BillingIssue;

use App\Repository\UsersRepository;
use App\Services\Customers\Account\BillingProfile;

Class User extends BillingProfile
{   
    public function __construct(int $userId)
    {   
        $this->userId = $userId;
        $this->user = new UsersRepository;
        $this->user->setRow($userId);
        $this->user->setRowAddress($userId);
    }

    public function getCardId()
    {
        $card = array_reverse($this->user->getCardId());
        $default = $this->user->getCardDefault();

        return empty($default) ? $card[0]->id : $default;
    }

    public function getCardDefault()
    {
        return $this->user->getCardDefault();
    }

    public function getSavedCardId()
    {
        return $this->user->getCardId();
    }

    public function getContactId()
    {
        return $this->user->getContactId();
    }

    public function getDeliveryNotes()
    {
        return $this->user->getDeliveryNotes();
    }
    
    public function updateCardDefault(int $userId, $cardId)
    {
        return $this->user->updateCardDefault($userId, $cardId);
    }

    public function storeCardId($id, $last4)
    {
        $ids = $this->getSavedCardId();
        array_push($ids, array('id' => $id, 'last4' => $last4));
        
        return $this->user->details->where('user_id', $this->userId)
            ->update([
                'card_ids' => json_encode($ids)
            ]);
    }

    public function updateStatus(int $userId, string $status)
    {
        return $this->user->updateStatus($userId, $status);
    }

}


