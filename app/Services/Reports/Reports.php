<?php

namespace App\Services\Reports;

use App\Services\Session\Session as SessionStorage;
use Request;
use Zipper;
use Storage;

use App\Services\Reports\ReportEcustomerExclusion;

Trait Reports
{     
	protected function getLocation()
	{
		return (new \App\Repository\ZoneRepository)->getAll();
	}

	protected function generate()
	{	
		$this->setZipTitle($this->getRequestTitle());

		$this->storeFiles();

		$this->createZip();

		$this->removeDirectory();

		return $this->download();
	}

	protected function storeFiles()
	{	
		if ($this->isCompleteReport()) 
		{
			$locationReportChecker = new ReportEcustomerExclusion($this->request);
			foreach($this->getLocation() as $row) {

				if (! $locationReportChecker->isLocationHaveCustomer($row->id)) continue;

				// Create customer report by location
				$this->storeCustomerReport($row->id, $this->cleanTitle($row->zone_name));	
				// Create pickslips report by location
				$this->storePickslipsReport($row->id, $this->cleanTitle($row->zone_name));
			}

			// // Create combine customer report 
			$this->storeCustomerReport();
		}

		if ($this->isKitchenOnly() || $this->isCompleteReport()) {
			// Create Combine Kitchen Report
			$this->storeCombineKitchenReport();

			$this->storeKitchenMealSplitReport();
		}

	}

	protected function createZip()
	{
		$files = glob(storage_path('app/'.$this->getDestination()).'*');

		Zipper::make(storage_path('app/'.$this->getRoot().$this->getZipTitle()))->add($files)->close();
	}

	protected function download()
	{
		$file = storage_path('app/'.$this->getRoot().$this->getZipTitle());
		$session = new SessionStorage('report-file');
		$session->store([$file]);
		
		return response()->download($file);
	}

	protected function removeDirectory()
	{	
		Storage::deleteDirectory(substr($this->getDestination(), 0, -1));
	}

	protected function setZipTitle($title)
	{
		$this->zipTitle = $title;
	}

	protected function setFileTitle($title)
	{
		$this->fileTitle = $title;
	}

	protected function getZipTitle()
	{
		return $this->zipTitle.$this->getTrimDate().'.zip';
	}

	protected function getTrimDate()
	{
		if (empty($this->trimDate))
			$this->trimDate = date('YmdHis');

		return $this->trimDate;
	}

	protected function getSlashDate()
	{
		if (empty($this->slashDate))
			$this->slashDate = date('Y/m/d/');

		return $this->slashDate;
	}

	protected function getRoot()
	{
		return $this->getExportTo().$this->getSlashDate();
	}

	protected function getFileTitle()
	{
		return $this->fileTitle;
	}

	protected function getCurrentDate()
	{
		if (empty($this->date))
			$this->date = date('YmdHis');

		return $this->date;
	}

	protected function getDestination() {
		if (empty($this->destination))
			$this->destination = $this->getRoot().$this->getTrimDate().'/';

		return $this->destination;
	}

	protected function timings()
	{
		return (new \App\Repository\TimingRepository)->getAllActive();
	}

	protected function cleanTitle($title)
	{
		return preg_replace("/(['\"\])([!~.?%^`])/", '', $title);
	}

}

