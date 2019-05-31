<?php

namespace App\Services;

use App\Services\InfusionSoftServices;
use Log;

Class Cards
{   
    
    public function __construct()
    {
        $this->userDetails = new \App\Models\UserDetails;
    }

    public function save()
    {
        
    }

    public function getSavedCards(int $userId)
    {   
        if (empty($userId)) return [];
        
        $card = [
            array('id' => 143, 'last4' => '0001'),
            array('id' => 143, 'last4' => '0001'),
            array('id' => 143, 'last4' => '0001'),
            array('id' => 143, 'last4' => '0001'),
            array('id' => 143, 'last4' => '0001')
        ];

        $list = $this->getSavedCardIds($userId);
    
        $cards = [];
        foreach($list as $row) {
            $row = is_array($row) ? (object)$row : $row; 
            $cards[] = ['id' => $row->id,'last4' => $row->last4];
        }
        
        return $cards;
    }

    public function updateDefaultCard(int $userId, $cardId)
    {   
        if (empty($userId)) return false;
        if (empty($cardId)) return false;

        return $this->userDetails->where('user_id', $userId)
                ->update([
                    'default_card' => $cardId
                ]);
    }

    public function getSavedCardIds(int $userId)
    {
        $details = $this->userDetails->where('user_id', $userId)->first();
        return !empty($details->card_ids) ? json_decode($details->card_ids) : [];
    }

    public function getContactId(int $userId)
    {
        $id = $this->userDetails->where('user_id', $userId)->first();
        return !empty($id->ins_contact_id) ? $id->ins_contact_id : '';
    }

    public function getEmail(int $userId)
    {
        return  $this->userDetails->email($userId);
    }

    public function getContact($email)
    {
        return $this->queryTable('Contact', array("Email"=>$email), false);
    }
}
