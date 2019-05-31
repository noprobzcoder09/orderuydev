<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Services\Card\Manager;
use App\Services\Card\ContactManager;
use App\Services\Card\Card as CardAdapter;
use App\Services\Card\User as UserModel;
use App\Services\Card\Traits\Card as TraitCard;
use App\Services\Card\INFS;
use App\Services\Customers\Checkout\User as UserCheckoutModel;
use App\Services\Customers\Account\Validator\Factory as CustomerValidatorFactory;
use Request;
use Auth;
use App\Traits\Auditable;
use App\Models\Users;

class Card extends Controller
{   
    use Auditable;
    /*
    |--------------------------------------------------------------------------
    | Card Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling new card
    | It uses a flexibile user id to add new card
    | Thus, it is being use in admin customer page adding new card
    |
    */

    use TraitCard;

    public function create()
    {
        try 
        {   
            $this->userCheckout = new UserCheckoutModel;
            $this->card = new CardAdapter;
            $this->user = new UserModel(Request::get('userId'));
            $this->infs = new INFS;

            // Validate Customer Details and Address if they exist
            $customerFactory = new CustomerValidatorFactory($this->user->getId());
            $customerFactory->account();

            $this->setCard();

            if (empty($this->user->getContactId()))
            {
                $contact = new ContactManager($this->getContact());
                $contact->store();
                $this->card->setContactId($contact->getId());
                $this->userCheckout->updateContactId(
                    Request::get('userId'),
                    $contact->getId()
                );
            }
            
            $manager = new Manager($this->card, $this->infs);

            $manager->setForceStore(true);

            $manager->store();

            $this->user->storeCardId($manager->getId(), $manager->getLast4());

            $custom_user = Users::find(Request::get('userId'));
            $this->audit('Added New Card for '.$custom_user->name. '.', 'This user '.$custom_user->name . ' added his/her new card.', '');

            return ['success' => true, 'message' => sprintf(__('crud.created'),'card')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function setCard()
    {
        $this->card->setCardNumber($this->getCardNumber());
        $this->card->setExpMonth($this->getExpMonth());
        $this->card->setExpYear($this->getExpYear());
        $this->card->setCVC($this->getCardCVC());
        $this->card->setType($this->getCardNumber());
        $this->card->setName($this->getCardName());
        $this->card->setFirstName($this->getFirstName());
        $this->card->setLastName($this->getLastName());
        $this->card->setBillName($this->getBillName());
        $this->card->setBillAddress1($this->getBillAddress1());
        $this->card->setBillAddress2($this->getBillAddress2());
        $this->card->setBillCity($this->getBillCity());
        $this->card->setBillState($this->getBillState());
        $this->card->setBillZip($this->getBillZip());
        $this->card->setBillCountry($this->getBillCountry());
        $this->card->setPhoneNumber($this->getPhoneNumber());
        $this->card->setEmail($this->getEmail());
        if (!empty($this->user->getContactId())) {
            $this->card->setContactId($this->user->getContactId());
        }
    }

    private function getContact()
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
            "PostalCode" => $this->getBillZip(),
            "DateCreated" => date('d-m-Y'),
        ];
    }

}
