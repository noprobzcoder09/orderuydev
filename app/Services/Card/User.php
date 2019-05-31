<?php

namespace App\Services\Card;

use App\Services\Card\Contracts\User as UserInterface;
use App\Repository\UsersRepository;

Class User extends UsersRepository implements UserInterface
{   
    private $userId;
    public function __construct(int $userId)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->setRow($this->userId);
        $this->setRowAddress($this->userId);
    }

    public function getId()
    {
        return $this->userId;
    }

    public function storeCardId($id, $last4)
    {
        $ids = $this->getCardId();
        array_push($ids, array('id' => $id, 'last4' => $last4));
        
        return $this->details->where('user_id', $this->userId)
            ->update([
                'card_ids' => json_encode($ids)
            ]);
    }

    public function storeAndUpdateDefaultCardId($id, $last4)
    {
        $ids = $this->getCardId();
        array_push($ids, array('id' => $id, 'last4' => $last4));
        
        return $this->details->where('user_id', $this->userId)
            ->update([
                'card_ids' => json_encode($ids),
                'default_card' => $id
            ]);
    }

    

}
