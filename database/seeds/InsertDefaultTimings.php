<?php

use Illuminate\Database\Seeder;

class InsertDefaultTimings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        	[
        		'delivery_day' => 'Wednesday',
	    		'cutoff_day' => 'Sunday',
	    		'cutoff_time' => '11:55 pm'
        	],
        	[
        		'delivery_day' => 'Sunday',
	    		'cutoff_day' => 'Thursday',
	    		'cutoff_time' => '11:55 pm'
        	]
    	];
        
    	foreach($data as $row) {
    		$row['cutoff_time'] = date('H:i', strtotime($row['cutoff_time']));
            if (DB::table('delivery_timings')->where($row)->count() <= 0) {
        		DB::table('delivery_timings')
            	->insert($row);
            }
    	}
    }
}
