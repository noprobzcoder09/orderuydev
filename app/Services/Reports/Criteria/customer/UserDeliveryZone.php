<?php

namespace App\Services\Reports\Criteria\Customer;

use App\Services\Reports\Joins;

Class UserDeliveryZone extends Joins
{      
	public function apply($model)
	{	

                $model->addSelect([
                        'delivery_zones.zone_name',
                        'delivery_zones.delivery_address'
                ]);
                
                $model->join('delivery_zone_timings',
                        'delivery_zone_timings.id', '=', 'user_details.delivery_zone_timings_id'
                );

                $model->join('delivery_zones',
                        'delivery_zones.id', '=', 'delivery_zone_timings.delivery_zone_id'
                );

                return $model;
	}
}

