<?php

namespace App\Services\Reports\Criteria\Pickslips;

use App\Services\Reports\Request;
use App\Services\Reports\Joins;

Class WithUserAddress extends Joins
{      
	public function __construct()
	{
		
	}
	
	public function apply($model)
	{	
        return $model->join('user_addresses',
            'user_addresses.user_id','=',
            'user_details.user_id'
        );
	}
}

