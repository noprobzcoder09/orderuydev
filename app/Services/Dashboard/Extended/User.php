<?php

namespace App\Services\Dashboard\Extended;

use Auth;

use App\Models\Users;
use App\Models\UserDetails;
use App\Models\UserAddress;

use App\Repository\SubscriptionSelectionsRepository;
use App\Repository\ZTRepository;
use App\Repository\CycleRepository;

Class User
{   
    public function __construct(int $userId)
    {
        $this->user = new Users;
        $this->user->find($userId);
        $this->details = new UserDetails;
        $this->details = $this->details->where(['user_id' => $userId])->first();
        $this->address = new UserAddress;
        $this->address = $this->address->where(['user_id' => $userId])->first();
        $this->id = $userId;

        $this->subscriptionCycleRepository = new SubscriptionSelectionsRepository;
        $this->zoneTimingRepository = new ZTRepository;
        $this->cycleRepository = new CycleRepository;
    }


    public function storeCardId($id)
    {
        
        $ids = $this->user->getCardId($this->getId());
        
        array_push($ids, $id);
        
        return $this->customer->updateCards($this->getId(), $id);
    }

    public function getContactId()
    {
        return $this->details->ins_contact_id ?? 0;
    }

    public function getFirstName()
    {
        return $this->details->billing_first_name ?? '';
    }

    public function getLastName()
    {
        return $this->details->billing_last_name ?? '';
    }

    public function getBillName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function getBillAddress1()
    {
        return $this->address->address1 ?? '';
    }

    public function getBillAddress2()
    {
        return $this->address->address2 ?? '';
    }

    public function getBillCity()
    {
        return $this->address->suburb ?? '';
    }

    public function getBillState()
    {
        return $this->address->state ?? '';
    }

    public function getBillZip()
    {
        return $this->address->postcode ?? '';
    }

    public function getBillCountry()
    {
        return $this->address->country ?? '';
    }

    public function getPhoneNumber()
    {
        return $this->details->mobile_phone ?? '';
    }

    public function getEmail()
    {   
        return $this->user->email($this->id);
    }


    public function getDeliveryNotes()
    {   
        return $this->details->delivery_notes ?? '';
    }  

    public function updateDeliveryZoneTiming(int $deliveryZoneTimingId)
    {
        return $this->details->where('user_id',$this->id)
        ->update([
            'delivery_zone_timings_id' => $deliveryZoneTimingId
        ]);
    }

    public function updateDeliveryNotes(string $notes = '')
    {
        return $this->details->where('user_id',$this->id)
        ->update([
            'delivery_notes' => $notes
        ]);
    }

    public function updateProfile(array $data)
    {
        return $this->details->where('user_id',$this->id)
        ->update($data);
    }

    public function updatePassword(string $password)
    {
        return $this->user->where('id',$this->id)
        ->update([
            'password' => bcrypt($password)
        ]);
    }

    public function getUserInfo()
    {
        return [
            "Email" => $this->getEmail(),
            "FirstName" => $this->getFirstName(),
            "LastName" => $this->getLastName(),
            "Phone1" => $this->getPhoneNumber(),
            "State" => $this->getBillState(),
            "Country" => $this->getBillCountry(),
            "City" => $this->getBillCity(),
            "StreetAddress1" => $this->getBillAddress1(),
            "StreetAddress2" => $this->getBillAddress2(),
            "PostalCode" => $this->getBillZip()
        ];
    }

    public function updateCurrentSubscriptionWeek(int $deliveryZoneTimingId)
    {
        $deliveryTimingId = $this->zoneTimingRepository->getTimingsIdById(
            $deliveryZoneTimingId
        );
        $deliveryZoneId = $this->zoneTimingRepository->getDeliveryZoneIdById(
            $deliveryZoneTimingId
        );
        
        $cycleId = $this->cycleRepository->getActiveByTimingId($deliveryTimingId);
        $cycleId = $cycleId->id ?? 0;

        if (empty($cycleId)) {
            throw new \Exception(__("Could not update current subscription week. Unknown Cycle Id."), 1);
        }

        return $this->subscriptionCycleRepository->updateCurrentSubscriptionWeekCycleId(
            $this->id, $cycleId, $deliveryZoneId
        );
    }
    

}


