<?php

namespace App\Services\Sync;


Class InfusionsoftContact
{       
    private $id = array();
    private $contacts = array();
    private $fields = array();

    public function __construct(array $id, array $contacts, array $fields)
    {
        $this->id = $id;
        $this->contacts = $contacts;
        $this->fields = $fields;
    }

    public function syncId() {
        return $this->id;
    }

    public function syncContacts() {
        return $this->contacts;
    }

    public function syncFields() {
        return $this->fields;
    }

}
