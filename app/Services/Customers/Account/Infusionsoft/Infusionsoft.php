<?php

namespace App\Services\Customers\Account\Infusionsoft;

use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\InfusionsoftV2\CustomField;
use App\Services\InfusionsoftV2\Tag;
use App\Services\Sync\SyncAbstract;
use App\Services\Sync\Sync;
use Log;

class Infusionsoft
{      
    private $infusionsoft;

    public function __construct()
    {
        $this->infusionsoft = (new InfusionsoftFactory('oauth2'))->service();
        $this->field = new CustomField;
        $this->tag = new Tag;
    }

    public function updateCustomerInfs(
        array $user = array(),
        string $activeMenu,
        string $activeLocation,
        string $activeDeliveryAddress,
        \DateTime $activeDate,
        \DateTime $ActiveWeekCutOffDate
    ) {

        $this->updateFields($user['contactId'], array(
                $this->field->getActiveWeekMenu() => $activeMenu,
                $this->field->getActiveLocation() => $activeLocation,
                $this->field->getActiveWeekDeliveryDatePretty() => $activeDate->format('l jS F'),
                $this->field->getActiveWeekDeliveryDate() => $activeDate->format('Y-m-d H:i:s'),
                $this->field->getActiveWeekCutOff() => $ActiveWeekCutOffDate->format('Y-m-d H:i'),
                $this->field->getActiveWeekCutOffDate() => $ActiveWeekCutOffDate->format('Y-m-d'),
                $this->field->getActiveDeliveryAddress() => $activeDeliveryAddress,
                'FirstName' => $user['firstName'],
                'LastName' => $user['lastName'],
                'Email' => $user['email'],
                'Phone1' => $user['phone']
            )
        );
        Log::info($ActiveWeekCutOffDate->format('Y-m-d H:i'));
    }

    public function updateCustomerDeliveryDetailsInfs(
        int $contactId,
        string $deliveryMenu,
        string $nextDeliveryLocation,
        string $deliveryAddress,
        \DateTime $nextDeliveryDate

    ) {

        $this->updateFields($contactId, array(
                $this->field->getDeliveryMenu() => $deliveryMenu,
                $this->field->getNextDeliveryLocation() => $nextDeliveryLocation,
                $this->field->getDeliveryAddress() => $deliveryAddress,
                $this->field->getNextDeliveryDate() => $nextDeliveryDate->format('Y-m-d H:i:s'),
                $this->field->getDeliveryDatePretty() => $nextDeliveryDate->format('l jS F')
            )
        );

    }

    public function updateCustomerDeliveryMenuOnlyInfs(int $contactId, string $deliveryMenu)
    {

        $this->updateFields($contactId, array(
                $this->field->getDeliveryMenu() => $deliveryMenu
            )
        );
    }

    public function updateCustomerDeliveryLocationWithAddressOnlyInfs(
        int $contactId, 
        string $deliveryLocation, 
        string $deliveryAddress
    ) {

        $this->updateFields(
            $contactId,
            array(
                $this->field->getActiveLocation() => $deliveryLocation,
                $this->field->getDeliveryAddress() => $deliveryAddress,
            )
        );
    }

    public function updateStatus(
        int $contactId, 
        string $newStatus
    ) {
        $this->infusionsoft->grpAssign($contactId, $this->tag->get($newStatus));
    }

    public function updatePausedCancelledPlans(
        int $contactId, 
        string $plans,
        $pausedDate = null
    ) {
        if (is_null($pausedDate)) {
            $pausedDate = '';
        } else {
            $pausedDate = new \DateTime($pausedDate);
            $pausedDate = $pausedDate->format('Y-m-d');
        }

        $this->updateFields(
            $contactId,
            array(
                $this->field->getPausedCancelledPlans() => $plans,
                $this->field->getPausedTillDate() => $pausedDate
            )
        );
    }
    
    
    public function savedTagToContact($tag, array $contacts) 
    {
        $this->infusionsoft->addTagToContact($tag, $contacts);
    }

    public function updateCustomerActiveLocationWithAddress(
        $contactId, 
        string $activeLocation,
        string $activeAddress
    ) {

        $this->updateFields($contactId, array(
            $this->field->getActiveLocation() => $activeLocation,
            $this->field->getActiveDeliveryAddress() => $activeAddress
        ));
    }

    public function updateCustomerDeliveryLocationWithAddress(
        $contactId, 
        string $deliveryLocation,
        string $deliveryAddress
    ) {
        $this->updateFields($contactId, array(
            $this->field->getNextDeliveryLocation() => $deliveryLocation,
            $this->field->getDeliveryAddress() => $deliveryAddress
        ));
    }

    public function sync(SyncAbstract $sync)
    {   
        $sync = new Sync($sync);
        $sync->run($this->infusionsoft);
    }

    public function cancelledWeek($contactId) {
        $this->infusionsoft->grpAssign($contactId, $this->tag->getCancelledWeekId());
    }

    public function activeMenuDelivery($contactId) {
        $this->infusionsoft->grpAssign($contactId, $this->tag->getActiveMenuDeliveryId());
    }
    
    public function pausedAPlan($contactId) {
        $this->infusionsoft->grpAssign($contactId, $this->tag->getPlanPausedId());
    }

    public function cancelledAPlan($contactId) {
        $this->infusionsoft->grpAssign($contactId, $this->tag->getCancelledPlanId());
    }

    private function updateFields($contactId, array $fields)
    {
        $this->infusionsoft->updateCustomFields($contactId, $fields);
    }
}

