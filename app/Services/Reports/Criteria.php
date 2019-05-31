<?php

namespace App\Services\Reports;

use Request;

Trait Criteria
{     
	protected function applyCriteria($model, $criteria)
	{
		return $criteria->apply($model);
	}

}

