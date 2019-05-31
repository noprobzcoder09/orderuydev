<?php

namespace App\Services\Reports;

use Storage;
use App\Exports\CustomerReport;
use App\Exports\KitchenReport;
use App\Exports\PickslipsReport;
use App\Exports\KitchenMealSplitReport;
use Maatwebsite\Excel\Facades\Excel;

Trait ReportsCreator
{     
	protected function createNewReport($to)
	{
		Storage::copy('reports/reports.xls', $to);
	}

	protected function storeCustomerReport($locationId = '', $locationName = '')
	{	
		
		$title = !empty($locationId) ? $locationName.'_'.$this->customer() : $this->combineCustomer();
		$this->setFileTitle(
			$this->getDestination().$title.$this->getCurrentDate().'.xlsx'
		);
		
		$this->createNewReport($this->getFileTitle());
		
		// Customer Report
		Excel::store(
			new CustomerReport($locationId, $this->request),
			$this->getFileTitle()
		);
	}

	protected function storeCombineKitchenReport()
	{
		$this->setFileTitle(
			$this->getDestination().$this->combineKitchen().$this->getCurrentDate().'.xlsx'
		);
		$this->createNewReport($this->getFileTitle());

		// Kitchen Report
		Excel::store(
			new KitchenReport($this->request),
			$this->getFileTitle()
		);
	}

	protected function storeKitchenMealSplitReport()
	{
		$this->setFileTitle(
			$this->getDestination().$this->kitchenMealSplit().$this->getCurrentDate().'.xlsx'
		);
		$this->createNewReport($this->getFileTitle());

		// Kitchen Report
		Excel::store(
			new KitchenMealSplitReport($this->request),
			$this->getFileTitle()
		);
	}

	protected function storePickslipsReport($locationId = '', $locationName = '')
	{	
		$this->setFileTitle(
			$this->getDestination().$locationName.'_'.$this->pickSlips().$this->getCurrentDate().'.xlsx'
		);
		$this->createNewReport($this->getFileTitle());
		
		Excel::store(
			new PickslipsReport($locationId, $this->request, $locationName),
			$this->getFileTitle()
		);
	}
}
