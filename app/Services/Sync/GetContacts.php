<?php

namespace App\Services\Sync;

use App\Services\InfusionsoftV2\OAuth2\OAuth2InfusionsoftService;

Class GetContacts
{       
    private $query;
    private $api;

    public function __construct(OAuth2InfusionsoftService $api, array $query)
    {
        $this->query = $query;
        $this->api = $api;
    }

    public function get()
    {
        $selected = array(
            'Id'
        );
          
        $contacts = array();
        foreach($this->api->queryTable('Contact', $this->query, $selected) as $row) {
            $row = is_array($row) ? (object)$row : $row;
            array_push($contacts, $row->Id);
        }

        return $contacts;
    }

}
