<?php

use Illuminate\Database\Seeder;

class InsertState extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	$data = [
    		'Victoria','New South Wales','Queensland','South Australia','Tasmania','Western Australia','Australian Capital Territory','Northern Territory'
    	];

    	$country = 'AU';

    	foreach($data as $row) 
    	{
    		if (DB::table('state')->where('state',$row)->count() <= 0) {
	            DB::table('state')
	            	->insert([
	            			'state_code' => '',
	            			'country_id' => DB::raw("(select id from country where country_code='{$country}')"),
	            			'state' => $row
	            		]);
	        }
    	}
    }
}
