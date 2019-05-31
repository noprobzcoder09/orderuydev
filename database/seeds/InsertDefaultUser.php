<?php

use Illuminate\Database\Seeder;

class InsertDefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data[] = [
    		'name' => 'Vic',
    		'email' => 'victo@fusedsoftware.com',
    		'password' => bcrypt('123456'),
    		'role' => 'administrator',
    		'active' => 1
    	];
        
    	foreach($data as $row) {
            if (DB::table('users')->where('email',$row['email'])->count() <= 0) {
        		DB::table('users')
            	->insert($row);
            }
    	}
    }
}
