<?php

namespace App\Services\Cutover\Traits;

Trait BilingRepositoryProvider
{   

    private function copyAndCreateSubscriptionSelections(int $subscriptionsCycleId, int $cycleId, $menuSelections, string $status)
    {   
        $this->selections->copyAndCreate($subscriptionsCycleId, $cycleId, $menuSelections, $status);
    }

    private function updateSubscriptionStatus($subscriptionId, $subscriptionsCycleId, string $status)
    {   
        $this->selections->setStatus($subscriptionsCycleId, $status);
        $this->subscriptions->setStatus(
            $subscriptionId, 
            $status == self::UNPAID_STATUS ? self::BILLING_ISSUE_STATUS : self::ACTIVE_STATUS
        );
    }

    private function resumeSubscriptions(array $subscriptions)
    {
        foreach($subscriptions as $row) {
            $this->updateSubscriptionStatus(
                $row->getSubscriptionId(),
                $row->getSubscriptionCycleId(),
                self::PENDING_STATUS      
            );
        }
    }

    private function updateBillingAttempt($userId, string $status = '')
    {
        $this->user->updateBillingAttempt($userId, $status);
    }

    private function resetBillingAttempt($userId)
    {
        $this->user->resetBillingAttempt($userId);
    }

    private function removePausedDate(array $packages)
    {
        foreach($packages as $row) 
        {
            $this->subscriptions->removePauseDate($row['subscription_id']);
        }
    }

    private function updateSubscriptionCycleInvoiceId($subscriptionsCycleId, $invoiceId)
    {
        $this->selections->updateInvoice($subscriptionsCycleId, $invoiceId);
    }

    private function updateInvoiceStatus($invoiceId, string $status)
    {
        $this->invoice->updateStatus($invoiceId, $status);
    }

    private function removeInvoice(array $invoice)
    {
        foreach($invoice as $key) 
        {
            $this->invoice->remove($key);
        }
    }

    private function storeInvoice(int $userId, $invoiceId, $orderId, $total, string $status)
    {       
        $this->invoice->store([
            'user_id' => $userId,
            'ins_invoice_id' => $invoiceId,
            'ins_order_id' => $orderId,
            'price' => $total,
            'status' => $status
        ]);
    }
}
