<?php

use Illuminate\Database\Seeder;

class InsertMeals extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	for($i = 1; $i <= 10; $i++)
    	{
    		if (DB::table('meals')->where('meal_name','Meal '.$i)->count() <= 0) {
	            DB::table('meals')
	            	->insert([
	            			'meal_sku'	=> 'MSKU '.$i,
				            'meal_name'	=> 'Meal '.$i,
				            'vegetarian' => 0,
				            'status'	=> 1
	            		]);
	        }
    	}

    	for($i = 11; $i <= 20; $i++)
    	{
    		if (DB::table('meals')->where('meal_name','Meal '.$i)->count() <= 0) {
	            DB::table('meals')
	            	->insert([
	            			'meal_sku'	=> 'MSKU '.$i,
				            'meal_name'	=> 'Meal '.$i,
				            'vegetarian' => 1,
				            'status'	=> 1
	            		]);
	        }
    	}
    }
}
