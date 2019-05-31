<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Infusionsoft\Api\DataService;
use Infusionsoft\Infusionsoft;


Class CreditCardService extends DataService
{  
    protected $table = 'CreditCard';

    protected static $tableFields = array('Id', 'ContactId', 'BillName', 'FirstName', 'LastName', 'PhoneNumber', 'Email', 'BillAddress1', 'BillAddress2', 'BillCity', 'BillState', 'BillZip', 'BillCountry', 'ShipFirstName', 'ShipMiddleName', 'ShipLastName', 'ShipCompanyName', 'ShipPhoneNumber', 'ShipAddress1', 'ShipAddress2', 'ShipCity', 'ShipState', 'ShipZip', 'ShipCountry', 'ShipName', 'NameOnCard', 'CardNumber', 'Last4', 'ExpirationMonth', 'ExpirationYear', 'CVV2', 'Status', 'CardType', 'StartDateMonth', 'StartDateYear', 'MaestroIssueNumber');

    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
    }

    public function getFields()
    {
        return self::$tableFields;
    }

    public function addCard(array $data): int
    {
        return $this->add($this->table, $data);
    }

    public function updateCard($id, array $data): array
    {
        return $this->update($this->table, $id, $data);
    }

    public function getCard(
        string $carId = null, 
        int $contactId = null, 
        array $queryData, 
        array $selectedFields,
        int $limit = 1000, 
        int $page = 0,
        string $orderBy = 'Id', 
        bool $ascending = true) {

        return $this->query($this->table, $limit, $page, $queryData, $selectedFields, $orderBy, $ascending);
    }
}
