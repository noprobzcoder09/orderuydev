<?php

namespace App\Services\Reports\Format;

use Request;

Class BorderPickslips
{     
	protected static $startIndex = 4;
	protected static $endIndex = 4;
	protected static $startColumn = 'A';
	protected static $endColumn = 'D';

	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function getColumns()
	{
		$ranges = [];
		$startIndex = static::$startIndex;
		$endIndex = static::$endIndex;
		foreach($this->data['customers'] as $row) {
			foreach($this->data['meals'][$row->user_id][$row->subscription_id] as $m) {
				$ranges[] = static::$startColumn.$startIndex.':'.static::$endColumn.$endIndex;
				$startIndex++;
				$endIndex++;
			}
			$startIndex+=4;
			$endIndex+=4;
		}

		return $ranges;
	}
}

