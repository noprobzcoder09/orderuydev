<?php

namespace App\Services\Customers\Account\Billing\Dto;


Class SubscriptionsCycleStatusDto
{   
    const PENDING_STATUS = 'pending';
    const PAUSED_STATUS = 'paused';
    const CANCELLED_STATUS = 'cancelled';
    const PAID_STATUS = 'paid';
    const UNPAID_STATUS = 'unpaid';
    const FAILED_STATUS = 'failed';

    public function __construct($status) {
        $this->status = strtolower($status);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isPending()
    {
        return $this->status == self::PENDING_STATUS;
    }

    public function isPaid()
    {
        return $this->status == self::PAID_STATUS;
    }

    public function isCancelled()
    {
        return $this->status == self::CANCELLED_STATUS;
    }

    public function isUnpaid()
    {   
       return $this->status == self::UNPAID_STATUS;
    }

    public function isFailed()
    {   
       return $this->status == self::FAILED_STATUS;
    }

    public function isPaused()
    {   
       return $this->status == self::PAUSED_STATUS;
    }

}
