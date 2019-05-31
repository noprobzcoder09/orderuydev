<?php

namespace App\Services\Sync;

use App\Services\Sync\Data;
use App\Services\Sync\Sync\Field;
use Auth;

Class Store
{       
    private $field;
    private $oldValue;
    private $newValue;
    private $api;
    private $group = '';
    private $isGroup = false;
    private $collections = array();

    const PENDING = 0;
    const COMPLETED = 1;
    const PROGRESS = 2;

    public function __construct()
    {
        $this->data = new Data;
    }

    public function store(string $field, $oldValue, $newValue)
    {
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
        
        array_push($this->collections, array(
            'field' => $this->field,
            'oldValue' => $this->oldValue,
            'newValue' => $this->newValue
        ));

        return $this;
    }

    public function handle()
    {   
        $this->generateGroup();
        foreach($this->collections as $row) {
            $row = is_array($row) ? (object)$row : $row;
            $this->data->store(
                $row->field,
                $row->oldValue,
                $row->newValue,
                '',
                selF::PENDING,
                $this->group,
                Auth::id()
            );
        }
    }

    private function generateGroup()
    {
        $this->group = str_shuffle(date('his'));
    }

}
