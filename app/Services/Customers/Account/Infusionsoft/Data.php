<?php

namespace App\Services\Customers\Account\Infusionsoft;

use App\Repository\SubscriptionRepository;
use App\Repository\MealsRepository;
use App\Repository\UsersRepository;

use App\Repository\CustomerRepository;
use App\Services\Customer;

class Data
{      
    private $userId;

    public function __construct(int $userId = 0)
    {
        $this->repo = new UsersRepository;
        $this->repo->setRow($userId);
        $this->userId = $userId;

        $this->customerRepo = new CustomerRepository;
        $this->customer = new Customer($this->customerRepo);

        $this->subscriptionRepo = new SubscriptionRepository;
    }
    
    public function getActiveMenu()
    {
        $subscription = new SubscriptionRepository;
        $meals = new MealsRepository;

        $data = '';
        foreach($subscription->getMyActivePlanName($this->userId) as $row) {
            $data .= $row->plan_name. "\n";
            $isDinner = $row->no_meals / $row->no_days;
            $selections = json_decode($row->menu_selections);
            $dinner = $isDinner == 1 ? $selections : array_splice($selections, $row->no_days, $row->no_meals);
            $lunch = $isDinner == 1 ? [] : array_splice($selections, 0, $row->no_days);
            
            if (count($lunch) > 0) {
                $data .=  "Lunch\n";
            }
            foreach($lunch as $id) {
               $meal = $meals->get($id);
                if (!empty($meal->meal_name)) {
                    $data .= $meal->meal_name. "\n";
                }
            }

            $data .=  "Dinner\n";
            foreach($dinner as $id) {
                $meal = $meals->get($id);
                if (!empty($meal->meal_name)) {
                    $data .= $meal->meal_name. "\n";
                }
            }
           

            $data .= "\n";
        }

        return $data;
    }

    public function getForDeliveryMenu()
    {
        $subscription = new SubscriptionRepository;
        $meals = new MealsRepository;

        $data = '';
        foreach($subscription->getMyLastActivePlanName($this->userId) as $row) {
            $data .= $row->plan_name. "\n";
            $isDinner = $row->no_meals / $row->no_days;
            $selections = json_decode($row->menu_selections);
            $dinner = $isDinner == 1 ? $selections : array_splice($selections, $row->no_days, $row->no_meals);
            $lunch = $isDinner == 1 ? [] : array_splice($selections, 0, $row->no_days);
            
            if (count($lunch) > 0) {
                $data .=  "Lunch\n";
            }
            foreach($lunch as $id) {
               $meal = $meals->get($id);
                if (!empty($meal->meal_name)) {
                    $data .= $meal->meal_name. "\n";
                }
            }

            $data .=  "Dinner\n";
            foreach($dinner as $id) {
                $meal = $meals->get($id);
                if (!empty($meal->meal_name)) {
                    $data .= $meal->meal_name. "\n";
                }
            }
           

            $data .= "\n";
        }


        return $data;
    }

    public function getId()
    {
        return $this->userId;
    }

    public function getContactId()
    {
        return $this->repo->getContactId();
    }

    public function getActiveLocation()
    {
        return $this->repo->getActiveLocation();
    }

    public function getLastActiveDeliveryLocation()
    {
        return $this->repo->getLastActiveDeliveryLocation();
    }

    public function getActiveDeliveryAddress()
    {
        return $this->repo->getDeliveryAddress();
    }

    public function getLastActiveDeliveryAddress()
    {
        return $this->repo->getLastActiveDeliveryAddress();
    }

    public function getFirstName()
    {
        return $this->repo->getFName();
    }

    public function getLastName()
    {
        return $this->repo->getLastName();
    }

    public function getEmail()
    {
        return $this->repo->getEmail($this->getId());
    }

    public function getMobilePhone()
    {
        return $this->repo->getMobilePhone();
    }

    public function getStatus()
    {
        return $this->repo->getStatus($this->getId());
    }

    public function checkStatus()
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return $customer->checkStatus($this->getId());
    }

    public function updateStatus(string $status = '')
    {
        return $this->repo->updateStatus($this->userId, $status);
    }

    public function getActiveWeekCutOffDate()
    {
        return new \DateTime(date($this->repo->getActiveWeekCutOffDate()));
    }
    
    public function getActiveWeekDate()
    {
        return new \DateTime(date($this->repo->getActiveWeekDate()));
    }

    public function getLastActiveWeekDeliveryDate()
    {   
        return new \DateTime(date($this->repo->getLastActiveWeekDeliveryDate()));
    }

    public function getUserInfo()
    {
        return [
            "Email" => $this->getEmail(),
            "FirstName" => $this->getFirstName(),
            "LastName" => $this->getLastName(),
            "Phone1" => $this->repo->getMobilePhone(),
            "State" => $this->repo->getBillState(),
            "Country" => $this->repo->getBillCountry(),
            "City" => $this->repo->getBillCity(),
            "StreetAddress1" => $this->repo->getBillAddress1(),
            "StreetAddress2" => $this->repo->getBillAddress2(),
            "PostalCode" => $this->repo->getBillZip()
        ];
    }

    public function isCancelledLastWeek()
    {   
        return $this->customer->isCancelledLastWeek($this->getId());
    }

    public function getPausedCancelledPlans()
    {   
        $plans = array();
        foreach($this->subscriptionRepo->getPausedCancelledPlans($this->getId()) as $row) {
            array_push($plans, $row->plan_name);
        }

        return implode(",", $plans);
    }

    public function getPausedDate()
    {   
        return $this->subscriptionRepo->getPausedDate($this->getId());
    }
    
    
}

