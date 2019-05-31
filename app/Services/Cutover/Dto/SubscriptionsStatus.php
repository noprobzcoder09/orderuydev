<?php

namespace App\Services\Cutover\Dto;


Class SubscriptionsStatus
{   
    const BILLING_ISSUE_STATUS = 'billing issue';
    const PAUSED_STATUS = 'paused';
    const CANCELLED_STATUS = 'cancelled';
    const ACTIVE_STATUS = 'active';

    public function __construct($status, \DateTime $pausedDate = null, \DateTime $deliveryDate = null) {
        $this->status = strtolower($status);
        $this->pausedDate = $pausedDate;
        $this->deliveryDate = $deliveryDate;
    }

    public function getStatus()
    {
        if ($this->isPaused()) {
            return SELF::PAUSED_STATUS;
        }

        if ($this->isBillingIssue()) {
            return SELF::BILLING_ISSUE_STATUS;
        }

        if ($this->isCancelled()) {
            return SELF::CANCELLED_STATUS;
        }

        return SELF::ACTIVE_STATUS;
    }

    public function isActive()
    {
        return $this->status == self::ACTIVE_STATUS;
    }

    public function isBillingIssue()
    {
        return $this->status == self::BILLING_ISSUE_STATUS;
    }

    public function isCancelled()
    {
        return $this->status == self::CANCELLED_STATUS;
    }

    public function isPaused()
    {   
        if ($this->status == self::PAUSED_STATUS) {
            if ($this->pausedDate >= $this->deliveryDate) {
                return true;
            }
        }

        return false;
    }

}
