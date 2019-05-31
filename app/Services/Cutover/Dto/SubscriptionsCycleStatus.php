<?php

namespace App\Services\Cutover\Dto;


Class SubscriptionsCycleStatus
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
        if ($this->isPaused()) {
            return SELF::PAUSED_STATUS;
        }

        if ($this->isPaid()) {
            return SELF::PAID_STATUS;
        }

        if ($this->isCancelled()) {
            return SELF::CANCELLED_STATUS;
        }

        if ($this->isUnpaid()) {
            return SELF::UNPAID_STATUS;
        }

        if ($this->isFailed()) {
            return SELF::FAILED_STATUS;
        }

        return SELF::PENDING_STATUS;
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
