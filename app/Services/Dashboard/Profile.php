<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Extended\Request;
use App\Services\Dashboard\Extended\User;
use App\Services\Validator;

use App\Services\Customers\Account\InfusionsoftCustomer;

class Profile
{      
    public function __construct(int $userId)
    {
        $this->id = $userId;
        $this->user = new User($userId);
        $this->request = new Request;
    }

    public function update()
    {
        try 
        {   
            $this->validate();
            $this->user->updateProfile($this->data());

            $infusionsoftCustomer = new InfusionsoftCustomer($this->id);
            $infusionsoftCustomer->updateCustomerInfs();

            return ['success' => true, 'message' => sprintf(__('crud.updated'),'profile')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function data()
    {
        return [
            'first_name' => $this->request->getFirstName(),
            'last_name' => $this->request->getLastName(),
            'mobile_phone' => $this->request->getPhoneNumber()
        ];
    }

    private function validate()
    {
        $validator = new Validator;
        $validator->validate($this->data(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_phone' => 'required'
        ]);

        if (!$validator->isValid()) {
            throw new \Exception($validator->filterError($validator->getMessage()), 1);
            
        }
    }
}

