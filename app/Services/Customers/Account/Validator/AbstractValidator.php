<?php

namespace App\Services\Customers\Account\Validator;

use App\Repository\CustomerRepository;

Abstract Class AbstractValidator
{	
	public $userId;
	public $customerModel;

	public function __construct(int $userId)
	{	
		$this->customerModel = new CustomerRepository;
		$this->userId = $userId;
		$this->load();
	}

	public abstract function load();
}