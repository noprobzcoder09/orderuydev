<?php

namespace App\Services\Customers\Account\Validator;

use App\Services\Customers\Account\Validator\AbstractValidator;

Class Account extends AbstractValidator
{	
	public function load()
	{  
        if (empty($this->userId)) {
            throw new \Exception(__('users.no_id'), 1);
        }
        
		if (!$this->customerModel->hasDetails($this->userId)) {
			throw new \Exception(__('users.no_details_record'), 1);
		}

		if (!$this->customerModel->hasAddres($this->userId)) {
			throw new \Exception(__('users.no_address_record'), 1);
		}
	}
}