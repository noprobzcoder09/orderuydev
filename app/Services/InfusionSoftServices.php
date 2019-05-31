<?php

namespace App\Services;


use Log;

Class InfusionSoftServices
{
    protected $api;

    public function __construct($type = 'oauth2')
    {
        $this->api = (new \App\Services\InfusionsoftV2\InfusionsoftFactory($type))->service();
    }

    public function fetchContactById(int $contactId): array
    {   
        return $this->api->fetchContactById($contactId);
    }

    public function fetchContactByEmail(string $email, array $fields = array()): array
    {   
        return $this->api->fetchContactByEmail($email, $fields);
    }
    
    public function deDupeContact(int $contactId = null, array $data): int
    {   
       return $this->api->deDupeContact($contactId, $data);
    }

    public function deDupeCard(int $cardId = null, array $data): int
    {   
        return $this->api->deDupeCard($cardId, $data);
    }

    public function optIn(string $email, string $optInReason = 'Opted In as per request'): bool
    {   
        return $this->api->optIn($email, $optInReason);
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

        return $this->api->fetchCard(
            $id, 
            $ContactId, 
            $Status, 
            $Last4, 
            $ExpiratonMonth, 
            '20'.$ExpirationsYear, 
            $orderBy, 
            $order
        );
    }

    public function addManualPayment(
        $invoiceId, 
        $amount, 
        $payDate = "", 
        $payType = "", 
        $invoiceNotes = "", 
        $bypassCommissions = false) {
        
        return $this->api->addManualPayment(
            $invoiceId, 
            $amount, 
            $payDate, 
            $payType, 
            $invoiceNotes, 
            $bypassCommissions
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

        return $this->api->queryTable(
            $table, 
            $query, 
            $selectedFields, 
            $limit, 
            $page, 
            $orderByField, 
            $ascending
        );
    }

    public function chargeInvoice(
        $InvoiceId, 
        $invoiceNotes, 
        $cardId, 
        $merchantAccountId, 
        $bypassCommissions = false) {

        return $this->api->chargeInvoice(
            $InvoiceId, 
            $invoiceNotes, 
            $cardId, 
            $merchantAccountId, 
            $bypassCommissions
        );
    }

    public function blankOrder (
        $contactId, 
        $description, 
        $orderDate = "", 
        $orderType = 'Online') {
        
        return $this->api->blankOrder(
            $contactId, 
            $description, 
            $orderDate, 
            $orderType
        );
    }


    public function addOrderItem(
        $orderId, 
        $productId, 
        $itemType, 
        $price, 
        $quantity, 
        $description = "", 
        $notes = "") {

        return $this->api->addOrderItem(
            $orderId, 
            $productId, 
            $itemType, 
            $price, 
            $quantity, 
            $description, 
            $notes
        );
    }

    public function getOrderItems($invoiceId) {
        return $this->api->getOrderItems($invoiceId);
    }
    
    public function grpAssign($contactId, $groupId): bool {
        return $this->api->grpAssign($contactId, $groupId);
    }

    public function updateCustomFields($contactId, $fields = array()){
        return $this->api->updateCustomFields($contactId, $fields);
    }

    public function addTagToContact(int $tagId, array $contacts){
        return $this->api->addTagToContact($tagId, $contacts);
    }

    public function updateCustomFieldValues(int $customFieldId, array $values){
        return $this->api->updateCustomFieldValues($customFieldId, $values);
    }

    public function calculateAmountOwed($invoiceId){
        return $this->api->calculateAmountOwed($invoiceId);
    }

    public function getPayments($invoiceId){
        return $this->api->getPayments($invoiceId);
    }
    
    public function getOrderItemsByOrderId($invoiceId){
        return $this->api->getOrderItems($invoiceId);
    }

    public function getInvoiceItems($invoiceId){
        return $this->api->getInvoiceItems($invoiceId);
    }
    
    
   
}
