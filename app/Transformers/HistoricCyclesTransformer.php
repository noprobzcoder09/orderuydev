<?php namespace App\Transformers;

use App\Traits\Shortable;
/**
 * AuditTrail Transformer
 */
class HistoricCyclesTransformer extends Transformer
{
	use Shortable;

	public function transform($item)
	{
		return [

			'id' 					=> $item['id'],

			'delivery_timings_id' 	=> $item['delivery_timings_id'],

			'delivery_date' 		=> $item['delivery_date'],

			'cutover_date'			=> $item['cutover_date'],

			'formatted' => [

				'delivery_date' => date('jS F Y',strtotime($item['delivery_date'])),

				'cutover_date' 	=> date('jS F Y',strtotime($item['cutover_date'])),

			],

			'status' 				=> $item['status'],

			'default_selections' 	=> $item['default_selections'],

			'default_selections_veg' => $item['default_selections_veg'],

			'batch' 				=> $item['batch'],

		];
	}
}
