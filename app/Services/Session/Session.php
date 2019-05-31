<?php

namespace App\Services\Session;

use App\Services\Session\AdapterInterface;
use Session as Sess;

Class Session implements AdapterInterface
{     
    const keyIdentifier = 'ultimatefyoufuel_';

    public function __construct(string $identifier)
    {   
        $this->identifier = $this->trim($identifier);
    }

    public function store(array $data)
    {   
        Sess::put($this->getId(), $data);
    }

    public function getId()
    {	
        return self::keyIdentifier.'_'.$this->identifier;
    }

    public function get()
    {   
       return Sess::get($this->getId()) ?? [];
    }

    public function iHaveData()
    {   
        return Sess::has($this->getId());
    }

    public function destroy()
    {  
        Sess::forget($this->getId());  
    }

    public function renew()
    {  
        $this->destroy($this->identifier);
    }

    private function trim(string $value)
    {
        return str_replace([' '], '', $value);
    }
}
