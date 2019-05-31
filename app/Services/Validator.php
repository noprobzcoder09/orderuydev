<?php

namespace App\Services;

use Request;
use Validator as Validate;

class Validator
{	
	public $messages = [];

	public $isValid = false;

	public function validate(array $data, array $rules): array
    {
        $validator = Validate::make($data, $rules);

         if ($validator->fails()) {
         	$this->isValid = false;
            
    		return $this->messages = array_map( function($message) { 
    			return $message;
    		}, 
    		(array)$validator->errors()->all());
    	}

    	$this->isValid = true;

    	return [];
    }

    public function filterError(array $messages)
    {
        return '<ul>'.array_reduce($messages, function($data, $message) {return $data .= '<li>'.$message.'</li>';}).'</ul>';
    }

    public function isValid()
    {
        return $this->isValid;
    }

    public function getMessage()
    {
        return $this->messages;
    }
}
