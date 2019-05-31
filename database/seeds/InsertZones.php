<?php

use Illuminate\Database\Seeder;

class InsertZones extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	$data = [
    		'Brunswick',
    		'Cheltenham',
    		'Footscray',
    		'Geelong North',
    		'Hallam',
    		'Hoppers Crossing',
    		'Mount Waverley',
    		'South Melbourne',
    		'Tullamarine'
    	];
        
    	foreach($data as $row) {
            if (DB::table('delivery_zones')->where('zone_name',$row)->count() <= 0) {
        		DB::table('delivery_zones')
            	->insert([
            			'zone_name' => $row
            		]);
            }
    	}
        
    }
}
