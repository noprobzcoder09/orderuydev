<?php namespace App\Transformers;

use App\Traits\Shortable;
/**
 * AuditTrail Transformer
 */
class AuditTrailTransformer extends Transformer
{
	use Shortable;

	public function transform($item)
	{
		return [

			'id' 				=> $item['id'],

			'title' 			=> $item['title'],

			'description' 		=> $item['description'],

			'additional_data'	=> $item['additional_data'],

			'summarized_description' => $this->short($item['description'], 20),

			'action_by' 		=> $item['action_by'],

			'user'				=> $item['user'],

			'ip_address' 		=> $item['ip_address'],

			'country' 			=> $item['country'],

			'device_name' 		=> $item['device_name'],

			'platform_name' 	=> $item['platform_name'],

			'browser_name' 		=> $item['browser_name'],

			'browser_version' 	=> $item['browser_version'],

			'created_at' 		=> date('M d, Y h:i A', strtotime($item['created_at'])),

		];
	}
}