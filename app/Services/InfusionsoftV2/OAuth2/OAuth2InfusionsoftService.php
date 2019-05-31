<?php

namespace App\Services\InfusionsoftV2\OAuth2;

use App\Repository\InfusionsoftAccountRepository;
use App\Services\InfusionsoftV2\OAuth2\OAuth2Service;
use App\Services\InfusionsoftV2\OAuth2\API\TableServiceProvider;

use App\Services\InfusionsoftV2\OAuth2\API\ContactService;
use App\Services\InfusionsoftV2\OAuth2\API\CreditCardService;
use App\Services\InfusionsoftV2\OAuth2\API\InvoiceService;
use App\Services\InfusionsoftV2\OAuth2\API\DataService;
use App\Services\InfusionsoftV2\OAuth2\API\OrderService;
use App\Services\InfusionsoftV2\OAuth2\API\OrderItemService;
use App\Services\InfusionsoftV2\OAuth2\API\TagService;
use App\Services\InfusionsoftV2\OAuth2\API\APIEmailService;

Class OAuth2InfusionsoftService extends OAuth2Service
{   
    use TableServiceProvider;

    public function __construct()
    {
        parent::__construct(new InfusionsoftAccountRepository);
    }    

    public function fetchContactById(int $contactId): array
    {   
        $contact = new ContactService($this->infussion);
        return $contact->load($contactId, ['FirstName','Id','Email']);
    }

    public function fetchContactByEmail(string $email, array $fields = array()): array
    {   
        $contact = new ContactService($this->infussion);
        return $contact->getContactByEmail($email, $fields);
    }

    public function deDupeContact(int $contactId = null, array $data): int
    {   
        $id = null;
        $contact = new ContactService($this->infussion);
        if (empty($contactId)) {
            $id = $contact->add($data);
        } else {
            $id = $contact->update($contactId, $data);
        }

        return $id;
    }

    public function deDupeCard(int $cardId = null, array $data): int
    {   
        $id = null;
        $card = new CreditCardService($this->infussion);
        if (empty($cardId)) {
            return $card->addCard($data);
        }

        return $card->updateCard($cardId, $data);

    }

    public function fetchCard(
        $id = null, 
        $ContactId = null, 
        $Status = null, 
        $Last4 = null, 
        $ExpiratonMonth = null, 
        $ExpirationsYear = null, 
        $orderBy = "Id", 
        $order = true): array {

        $card = new CreditCardService($this->infussion);

        $query = array();

        if($id != null && !empty($id)){
            $query["Id"] = $id;
        }
        if($ContactId != null && !empty($ContactId)){
            $query["ContactId"] = $ContactId;
        }
        if($Status != null && !empty($Status)){
            $query["Status"] = $Status;
        }
        if($Last4 != null && !empty($Last4)){
            $query["Last4"] = $Last4;
        }
        if($ExpiratonMonth != null && !empty($ExpiratonMonth)){
            $query["ExpirationMonth"] = $ExpiratonMonth;
        }
        if($ExpirationsYear != null && !empty($ExpirationsYear)){
            $query["ExpirationYear"] = $ExpirationsYear;
        }
        
        if(count($query) == 0) return array();
        
        $return = [
            "ContactId", 
            "NameOnCard", 
            "Id", 
            "Last4", 
            "Status", 
            "ExpirationMonth", 
            "ExpirationYear"
        ];


        return $card->getCard($id, $ContactId, $query, $return);
    }

    public function addManualPayment(
        $invoiceId, 
        $amount, 
        $payDate = "", 
        $payType = "", 
        $invoiceNotes = "", 
        $bypassCommissions = false) {
        
        $invoice = new InvoiceService($this->infussion);

        return $invoice->addManualPayment(
            $invoiceId, $amount, $payDate, $payType, $invoiceNotes, $bypassCommissions
        );

    }
    
    public function queryTable(
        string $table, 
        array $query, 
        $selectedFields = false, 
        int $limit = 1000, 
        int $page = 0, 
        string $orderByField = "Id", 
        bool $ascending = true) {

        $dataService = new DataService($this->infussion);

        if (empty($selectedFields) || !$selectedFields) {
            $tableService = $this->getTableServiceProvider($this->infussion, $table);
            $selectedFields = $tableService->getFields();
        }

        return $dataService->query(
            $table, $limit, $page, $query, $selectedFields, $orderByField, $ascending
        );
    }

    public function chargeInvoice(
        $InvoiceId, 
        $invoiceNotes, 
        $cardId, 
        $merchantAccountId, 
        $bypassCommissions = false) {
        

        $invoice = new InvoiceService($this->infussion);

        return $invoice->chargeInvoice(
            $InvoiceId, 
            $invoiceNotes, 
            $cardId, 
            $merchantAccountId, 
            $bypassCommissions = false
        );

    }

    public function blankOrder (
        $contactId, 
        $description, 
        $orderDate = "", 
        $orderType = 'Online') {
        
        
        if (!$orderDate instanceOf \DateTime) {
            $orderDate = empty($order_date) ? new \DateTime('now') : $orderDate;    
        }
        
        $order = new InvoiceService($this->infussion);

        $id = $order->createBlankOrder($contactId, $description, $orderDate, 0, 0);

        return $id;
    }


    public function addOrderItem(
        $orderId, 
        $productId, 
        $itemType, 
        $price, 
        $quantity, 
        $description = "", 
        $notes = "") {

        $order = new InvoiceService($this->infussion);

        $id = $order->addOrderItem(
            $orderId, 
            $productId, 
            $itemType, 
            $price, 
            $quantity, 
            $description, 
            $notes
        );


        return $id;
    }

    public function getOrderItems($orderId) {

        $order = new OrderItemService($this->infussion);

        return $order->getOrderItemsByOrderId($orderId);
    }

    public function getInvoiceItems($invoiceId) {

        $order = new OrderItemService($this->infussion);

        return $order->getOrderItemsByOrderId($invoiceId);
    }
    
    
    public function grpAssign($contactId, $groupId): bool {
        $contact = new ContactService($this->infussion);

        return $contact->addToGroup($contactId, $groupId);
    }

    public function updateCustomFields($contactId, $fields = array()){
        if(empty($contactId)) return false;
        if(count($fields) == 0){
            #Default values to be savedd for all custom fields for the contact 
            $fields = array(
                "_ActiveWeekMenu"=>"", 
                "_ActiveLocation"=>"", 
                "_NextDeliveryLocation"=>"", 
                "_DeliveryMenu"=>"", 
                "_PausedCancelledPlans"=>"", 
                "_ActiveWeekCutOffDate"=>"", 
                "_ActiveWeekCutOff"=>"", 
                "_NextDeliveryDate"=>"", 
                "_PausedTillDate"=>""
            );
        }
        
        #update not INFS, if it has data for updation 
        return $this->deDupeContact($contactId, $fields);
    }

    public function addToGroup($contactId, $groupId): bool {
        $contact = new ContactService($this->infussion);

        return $contact->addToGroup($contactId, $groupId);
    }

    public function addTagToContact(int $tagId, array $contacts)
    {
        $tag = new TagService($this->infussion);   

        $tag->id = $tagId;

        $collection = collect($contacts);
        
        $chunks = $collection->chunk(100);

        foreach($chunks->toArray() as  $row) {
            $tag->addContacts($row);
        }
    }

    public function updateCustomFieldValues(int $customFieldId, array $values)
    {
        $dataService = new DataService($this->infussion);   
        $dataService->updateCustomField($customFieldId, $values);

    }
    
    public function optIn(string $email, string $optInReason = 'Opted In as per request'): bool
    {
        $emailService = new APIEmailService($this->infussion);   
        return $emailService->optIn($email, $optInReason);
    }

    public function calculateAmountOwed($invoiceId)
    {
        $invoice = new InvoiceService($this->infussion);   
        return $invoice->calculateAmountOwed($invoiceId);
    }

    public function getPayments($invoiceId)
    {
        $invoice = new InvoiceService($this->infussion);   
        return $invoice->getPayments($invoiceId);
    }
    
    

}
