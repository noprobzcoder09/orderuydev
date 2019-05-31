<?php

namespace App\Services\Customers\Account\Validator;

use App\Services\Customers\Account\Validator\Account;

Class Factory
{
	private $userId;

	public function __construct(int $userId)
	{
		$this->userId = $userId;
	}

	public function account()
	{
		return new Account($this->userId);
	}
}