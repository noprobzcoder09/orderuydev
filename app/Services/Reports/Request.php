<?php

namespace App\Services\Reports;

use Request as Requestor;

Class Request
{     

	public function getDaterange() {
		return Requestor::get('daterange');
	}

	private function getDaterangeExplode() {
		return explode('-',$this->getDaterange());
	}

	public function getDateFrom() {
		$d = trim($this->getDaterangeExplode()[0]);
		return date('Y-m-d',strtotime($d));
	}

	public function getDateTo() {
		$d = isset($this->getDaterangeExplode()[1]) ? trim($this->getDaterangeExplode()[1]) : '';
		return date('Y-m-d',strtotime($d));
	}

	public function getParameter() {
		return Requestor::get('parameters');
	}

	public function getReport() {
		return Requestor::get('reports');
	}

	public function getLocation() {
		return Requestor::get('location');
	}

	public function getTiming() {
		return Requestor::get('timings');
	}

	public function getExportType() {
		return Requestor::get('export_type');
	}
	

}

