<?php

namespace App\Services\INFSBilling;

use App\Services\Log;

Class Invoice extends \App\Services\InfusionSoftServices
{   
    
    protected static $invoiceBillInvalidCodes = array('Declined','Error','Skipped');

    public function __construct(int $orderId, $api = null)
    {   
        if (is_null($api)) {
            parent::__construct();
        } else {
            $this->api = $api;
        }

        $this->log = new Log('billing','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');

        $id = $this->queryTable("Invoice", array("JobId"=>$orderId), array("Id"));

        $id = isset($id[0]['Id']) ? $id[0]['Id'] : '';

        if (empty($id)) {
            throw new \Exception(sprintf(__('crud.failedToCreate'),'Infusionsoft invoice id'), 1);

        }

        $this->setId($id);
    }

    public function create($merchantAccountId, $cardId, $invoiceNotes = '', bool $bypassCommissions = false)
    {   
        $this->log->info("Create Invoice with", array(
            'invoiceId' => $this->getId(),
            "parameters" => func_get_args()
        ));
        
        $invoice = $this->chargeInvoice($this->getId(), $invoiceNotes, $cardId, $merchantAccountId, $bypassCommissions);
        
        $this->log->info("Invoice charge result", $invoice);
        
        if (in_array(ucfirst(strtolower($invoice['Code'])), static::$invoiceBillInvalidCodes)) {
            throw new \Exception($invoice['Message'], 1);
        }
        
        if (empty($invoice['Successful'])) {
            throw new \Exception($invoice['Message'], 1);
        }

    }

    public function getOrderItems($invoiceId)
    {
        return $this->getOrderItemsByOrderId($invoiceId);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}

