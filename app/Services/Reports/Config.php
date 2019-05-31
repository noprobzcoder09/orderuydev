<?php

namespace App\Services\Reports;

use Request;

Trait Config
{     
	protected function getConfig()
	{
		return [
			'breadcrumb'    => $this->breadcrumb(),
	        'view'          => self::view,
	        'generateUrl'   => self::generateUrl,
	        'timingsUrl'		=> self::timingsUrl,
	        'types'         => $this->getTypes(),
	        'timings'      => $this->timings(),
	        'parameters'    => $this->getParameters()
		];
	}
}

