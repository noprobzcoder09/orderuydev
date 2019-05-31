<?php

namespace App\Services\Customers\Account\Billing\Dto;


Class SubscriptionsStatusDto
{   
    const BILLING_ISSUE_STATUS = 'billing issue';
    const PAUSED_STATUS = 'paused';
    const CANCELLED_STATUS = 'cancelled';
    const ACTIVE_STATUS = 'active';

    public function __construct(
        string $status, 
        \DateTime $pausedDate = null, 
        \DateTime $deliveryDate = null,
        \DateTime $currentDeliveryDate = null
    ) {
        $this->status = strtolower($status);
        $this->pausedDate = $pausedDate;
        $this->deliveryDate = $deliveryDate;
        $this->currentDeliveryDate = $currentDeliveryDate;
    }

    public function getStatus()
    {
        if ($this->isPaused()) {
            return self::PAUSED_STATUS;
        }

        if ($this->isBillingIssue()) {
            return self::BILLING_ISSUE_STATUS;
        }

        if ($this->isCancelled()) {
            return self::CANCELLED_STATUS;
        }

        return self::ACTIVE_STATUS;
    }

    public function isForBilling()
    {
        if (self::ACTIVE_STATUS == $this->getStatus()) {
            return true;
        }
        return false;
    }

    public function isPauseResumeNow()
    {
        $date = date('Y-m-d', strtotime($this->currentDeliveryDate->format('Y-m-d').' +1 week'));
        $date = new \DateTime($date);

        return $date >= $this->pausedDate;
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
