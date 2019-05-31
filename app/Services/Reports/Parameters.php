<?php

namespace App\Services\Reports;

use App\Services\Reports\Request;
use App\Models\Cycles;
use App\Transformers\HistoricCyclesTransformer;

Trait Parameters
{  
	protected static $parameters = [
		'Active/Current Cycle','Last Cycle'
	];

	protected $historic_cycle_transformer;

	protected function request()
	{	
		return $this->request;
	}

	protected function getHistoricCycles($timings_id){

		$transformer = new HistoricCyclesTransformer;

		$historic_cycles = [];

		$historic_cycles_found = Cycles::where('delivery_timings_id', $timings_id)->whereIn('status', ['-1', 1])->orderBy('delivery_date', 'DESC')->get();

		if ($historic_cycles_found->count() > 0) {
			foreach ($historic_cycles_found as $key => $historic_cycle) {
				if ($key > 1) {
					$historic_cycles[] = $transformer->transform($historic_cycle);
				}
			}
		}

		return $historic_cycles;
	}

	
	protected function getParameters() {
		return static::$parameters;
	}

	protected function getCurrentCycle() {
		return static::$parameters[0];
	}

	protected function getLastCycle() {
		return static::$parameters[1];
	}

	protected function getPreviousCycle(){
		return $this->request()->getParameter();
	}

	protected function isCurrentCycle() 
	{
		return ($this->getCurrentCycle() == $this->request()->getParameter());
	}

	protected function isLastCycle() 
	{	
		return ($this->getLastCycle() == $this->request()->getParameter());
	}

	protected function isCompleteReport() 
	{	
		return ('complete' == strtolower($this->request()->getExportType()));
	}

	protected function isKitchenOnly() 
	{	
		return ('kitchen-only' == strtolower($this->request()->getExportType()));
	}
}
