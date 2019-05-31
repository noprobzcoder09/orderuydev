<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Joins;

Class UserDetails extends Joins
{      
	public function apply($model)
	{	

        $model->addSelect([
            'user_addresses.address1',
            'user_addresses.address2',
            'user_addresses.suburb',
            'state.state',
            'user_addresses.country',
            'user_addresses.postcode'
        ]);
        
        $model->join('user_addresses',
                'user_details.user_id', '=', 'user_addresses.user_id'
        );

        $model->join('state',
                'state.id', '=', 'user_addresses.state'
        );

        return $model;
	}
}

