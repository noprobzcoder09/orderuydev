<?php

namespace App\Services\Reports\AutoEmail;

use App\Services\Reports\Request as ParentRequest;

Class Request
{     
    private $parameters;
    private $timings;

	public function getParameter() {
        return $this->parameters;
    }

	public function getTiming() {
        return $this->timings;
    }
    
    public function setParameter($parameters) {
        $this->parameters = $parameters;
    }

    public function setTiming($timings) {
        $this->timings = $timings;
    }


}

