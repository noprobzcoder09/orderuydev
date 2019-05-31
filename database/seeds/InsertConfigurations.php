<?php

use Illuminate\Database\Seeder;

class InsertConfigurations extends Seeder
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
        		'slug' => 'active-cycle-batch',
	    		'name' => 'Active Cycle Batch',
	    		'value' => '1'
        	]
    	];
        
    	foreach($data as $row) {
            if (DB::table('configurations')->where($row)->count() <= 0) {
        		DB::table('configurations')
            	->insert($row);
            }
    	}
    }
}
