<?php

namespace App\Services\Reports\Criteria;

use App\Services\Reports\Request;

Class WithContactId
{     
	public function __construct()
	{
		$this->request = new Request();
	}
	
	public function apply($model)
	{
		return $model->where('ins_contact_id','<>','');
	}
}

