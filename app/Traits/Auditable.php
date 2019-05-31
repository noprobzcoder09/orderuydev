<?php namespace App\Traits;

use App\Models\AuditLog;
use Jenssegers\Agent\Agent;
use Location;

trait Auditable{

	/**
	 * This will track the user's activity
	 * @param  string $title       		This will handle the specific activity
	 * @param  string $description 		This will describe the activity
	 * @param  string $additional_data 	This will be additional data like json objects|arrays, be sure to json_encode the data
	 * @param  integer $user_id    		This will the logged in user id
	 * @return void
	 */
	public function audit($title = '', $description = '', $additional_data = '', $user_id = NULL)
	{
		$agent = new Agent;

		if (is_null($user_id)) {
			$user_id = \Auth::id() ?: 0;
		}

		$ip_address = '127.0.0.1';

		$location = 'Not Detected';

		$audit = new AuditLog;
		$audit->title 			= $title;
		$audit->description 	= $description;
		$audit->additional_data = $additional_data;
		$audit->action_by 		= $user_id;
		$audit->ip_address 		= $ip_address;
		$audit->country 		= $location;
		$audit->device_name 	= $agent->device();
		$audit->platform_name 	= $agent->platform();
		$audit->browser_name 	= $agent->browser();
		$audit->browser_version = $agent->version($agent->browser());
		$audit->save();

	}

}


