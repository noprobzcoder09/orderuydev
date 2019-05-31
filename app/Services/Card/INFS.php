<?php

namespace App\Services\Card;

use App\Services\Card\Contracts\Gateway as GatewayInterface;
use App\Services\InfusionSoftServices;

Class INFS extends InfusionSoftServices implements GatewayInterface
{   
    public $cardFields = array(
        'ContactId',
        "NameOnCard",
        "CardNumber",
        "ExpirationMonth",
        "ExpirationYear",
        "FirstName",
        "LastName",
        "CVV2",
        'CardType',
        'BillName',
        'BillAddress1',
        // 'BillAddress2',
        'BillCity',
        'BillState',
        'BillZip',
        'BillCountry',
        'PhoneNumber',
        'Email'
    );

    public function __construct($api = null)
    {
        if (is_null($api)) {
            parent::__construct();
        } else {
            $this->api = $api;
        }
    }

    public function store(array $data)
    {   
        $id = $this->deDupeCard(null, $data);

        if (empty($id)) {
            throw new \Exception(sprintf(__('crud.failedToCreate'),'card'), 1);
            
        }

        return $id;
    }

    public function getId(int $contactId, string $cardNumber, string $month, string $year)
    {   
        $id = $this->fetchCard(
            null, 
            $contactId,
            null,
            substr($cardNumber,-4, 4),
            $month,
            $year
        );
        
        return isset($id[0]['Id']) ? $id[0]['Id'] : 0;
    }
}
