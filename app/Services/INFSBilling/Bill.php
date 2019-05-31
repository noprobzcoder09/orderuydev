<?php

namespace App\Services\INFSBilling;
use App\Services\Log;

Class Bill extends \App\Services\InfusionSoftServices
{   

    private $status = false;

    public function __construct(
        int $invoiceId, 
        float $amount, 
        \DateTime $orderDate, 
        string $payType = 'Credit Card', 
        $notes = '', 
        $api = null
    ) {
        if (is_null($api)) {
            parent::__construct();
        } else {
            $this->api = $api;
        }
        
        $this->log = new Log('billing','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');

        $orig_amount = $amount;
        if (env('BILLING_SANBOX', false)) {
            $amount = 0.1;
        }

        if ($amount <= 0) {
            throw new \Exception("Billing amount should be greater than to zero.", 1);
            
        }
        
        $this->log->info('Charge amount for invoice #'.$invoiceId. ' is '.$amount. '. Original amount is '.$orig_amount.'.');
        $this->status = $this->addManualPayment($invoiceId, $amount, $orderDate, $payType, $notes);
    }

    public function success(): bool
    {
        return !$this->status;
    }

}

