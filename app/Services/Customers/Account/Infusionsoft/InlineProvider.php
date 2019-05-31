<?php

namespace App\Services\Customers\Account\Infusionsoft;

use App\Services\Customers\Account\Infusionsoft\Infusionsoft;
use App\Services\Customers\Account\Infusionsoft\Data;
use App\Services\Card\ContactManager;
use App\Services\Sync\SyncAbstract;
use Log;
use App\Traits\Auditable;

class InlineProvider
{      
    use Auditable;

    public function __construct(Data $data)
    {
        $this->data = $data;
        $this->infusionsoft = new Infusionsoft;
    }

    public function updateCustomerInfs()
    {
        $user = array(
            'contactId' => $this->data->getContactId(),
            'firstName' => $this->data->getFirstname(),
            'lastName' => $this->data->getLastName(),
            'email' => $this->data->getEmail(),
            'phone' => $this->data->getMobilePhone()
        );

        $this->infusionsoft->updateCustomerInfs(
            $user,
            $this->data->getActiveMenu(),
            $this->data->getActiveLocation(),
            $this->data->getActiveDeliveryAddress(),
            $this->data->getActiveWeekDate(),
            $this->data->getActiveWeekCutOffDate()
        );
    }

    public function updateCustomerDeliveryDetailsInfs()
    {
        $this->infusionsoft->updateCustomerDeliveryDetailsInfs(
            $this->data->getContactId(),
            $this->data->getForDeliveryMenu(),
            $this->data->getLastActiveDeliveryLocation(),
            $this->data->getLastActiveDeliveryAddress(),
            $this->data->getLastActiveWeekDeliveryDate()
        );
    }

    public function updateCustomerDeliveryLocationWithAddressOnlyInfs() 
    {
        $this->infusionsoft->updateCustomerDeliveryLocationWithAddressOnlyInfs(
            $this->data->getContactId(),
            $this->data->getActiveLocation(),
            $this->data->getActiveDeliveryAddress()
        );
    }

    public function updateCustomerDeliveryMenuOnlyInfs()
    {
        $this->infusionsoft->updateCustomerDeliveryMenuOnlyInfs(
            $this->data->getContactId(),
            $this->data->getActiveMenu()
        );
    }

    public function updateStatus(string $newStatus = '')
    {
        $currentStatus = $this->data->getStatus();
        if (empty($newStatus)) {
            $newStatus = $this->data->checkStatus();
        }   
        Log::info(strtolower($newStatus) .'!='. strtolower($currentStatus));     
        if (strtolower($newStatus) != strtolower($currentStatus)) {
            $this->data->updateStatus($newStatus);
            $this->infusionsoft->updateStatus(
                $this->data->getContactId(),
                $newStatus
            );

            $this->audit('User Change Status', 'User change status from "'.$currentStatus.'" into "'.$newStatus.'".', '');
        }
    }

    public function updatePausedCancelledPlans()
    {
        $this->infusionsoft->updatePausedCancelledPlans(
            $this->data->getContactId(),
            $this->data->getPausedCancelledPlans(),
            $this->data->getPausedDate()
        );
    }

    public function savedTagToContact($tag, array $contacts)
    {
        $this->infusionsoft->savedTagToContact(
            $tag,
            $contacts
        );
    }

    public function updateContact()
    {   
        $contactId = $this->data->getContactId();
        $email = $this->data->getEmail();
        
        $contactManager = new ContactManager($this->data->getUserInfo());
        if (!empty($contactId)) {
            $contactManager->update($contactId);

            if (!empty($email)) {
                $contactManager->optInEmail($contactId);
            }
        }
    }

    public function updateCustomerActiveLocationWithAddress()
    {
        $this->infusionsoft->updateCustomerActiveLocationWithAddress(
            $this->data->getContactId(),
            $this->data->getActiveLocation(),
            $this->data->getActiveDeliveryAddress()
        );
    }

    public function updateCustomerDeliveryLocationWithAddress()
    {
        $this->infusionsoft->updateCustomerDeliveryLocationWithAddress(
            $this->data->getContactId(),
            $this->data->getActiveLocation(),
            $this->data->getActiveDeliveryAddress()
        );
    }

    public function updateCustomerSyncDeliveryZone(
        string $activeLocation = '',
        string $activeAddress = '',
        string $deliveryLocation = '',
        string $deliveryAddress = ''
    ) {
        $this->infusionsoft->updateCustomerSyncDeliveryZone(
            $this->data->getContactId(),
            empty($activeLocation) ? $this->data->getActiveLocation() : $activeLocation,
            empty($activeAddress) ? $this->data->getActiveDeliveryAddress() : $activeAddress,
            empty($deliveryLocation) ? $this->data->getLastActiveDeliveryLocation() : $deliveryLocation,
            empty($deliveryAddress) ? $this->data->getLastActiveDeliveryAddress() : $deliveryAddress
        );
    }

    public function sync(SyncAbstract $sync)
    {   
        $this->infusionsoft->sync($sync);
    }

    public function cancelledWeek() {
        if ($this->data->isCancelledLastWeek()) {
            $this->infusionsoft->cancelledWeek(
                $this->data->getContactId()
            );
        }
    }

    public function activeMenuDelivery() {
        if (!$this->data->isCancelledLastWeek()) {
            $this->infusionsoft->activeMenuDelivery(
                $this->data->getContactId()
            );
        }
    }

    public function pausedAPlan() {
        $this->infusionsoft->pausedAPlan(
            $this->data->getContactId()
        );
    }

    public function cancelledAPlan() {
        $this->infusionsoft->cancelledAPlan(
            $this->data->getContactId()
        );
    }

}

