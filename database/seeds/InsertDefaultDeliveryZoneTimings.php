<?php

use Illuminate\Database\Seeder;

class InsertDefaultDeliveryZoneTimings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach(DB::table('delivery_zones')->get() as $zone) 
    	{

    		foreach(DB::table('delivery_timings')->get() as $timing) 
    		{

	            if (DB::table('delivery_zone_timings')->where([
	            	'delivery_zone_id' => $zone->id,
	            	'delivery_timings_id' => $timing->id,
	            ])->count() <= 0) 
	            {
	        		DB::table('delivery_zone_timings')
	            	->insert([
	            		'delivery_zone_id' => $zone->id,
	            		'delivery_timings_id' => $timing->id,
	            	]);
	            }
        	}
    	}
    }
}
