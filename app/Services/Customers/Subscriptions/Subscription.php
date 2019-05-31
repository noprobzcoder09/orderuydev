<?php

namespace App\Services\Customers\Subscriptions;

use App\Services\INFSBilling\Manager as BillingManager;
use App\Services\Customers\Account\Validator\Factory as CustomerValidatorFactory;
use App\Services\Coupons\Validator\Factory;
use \App\Services\Validator;
use App\Traits\Auditable;
use App\Models\Users;
use App\Repository\SubscriptionRepository;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Manageplan\Worker;

use Auth;
use DB;

Trait Subscription
{    
    use Auditable;

    private $billing;

    public function getOrderSummary()
    {   
        return view(self::view.'customer.subscription-summary',[
            'worker'    => $this->worker,
            'order'     => $this->order->get(),
            'coupons'   => $this->coupon->get(),
        ]);
    }

    public function removeCoupon()
    {   
        $this->coupon->delete($this->request->getPromoCode());
    }

    public function updatePlan()
    {   
        $this->order->store($this->request->getPlanId());
    }

    public function createSubscription(int $userId)
    {   
        DB::beginTransaction();
        try 
        {   
            $this->setAuth($userId);

            $this->createValidate();

            $this->_createSubscription();

            $this->sessionFacade->destroy();
            
            DB::commit();

            $custom_user = Users::find($userId);

            $additional_information = '';

            $subscription_by_user = (new SubscriptionRepository)->getByUserIdOderDesc($userId);

            if ($subscription_by_user) {

                $additional_information .= 'Added Plan: ' . $subscription_by_user->meal_plan['plan_name'];

            }

            $this->audit('Created Subscription and Billed at Cutover this for '.$custom_user->name, 'Created a subscription for '.$custom_user->name . ' and Billed at Cutover.', $additional_information);


            $this->infusionsoftCustomerProvider();

            return $this->sendSuccessResponse();
        }
        catch (\Exception $e)
        {   
            DB::rollback();

            $custom_user = Users::find($userId);

            $additional_information = '';

            $subscription_by_user = (new SubscriptionRepository)->getByUserIdOderDesc($userId);

            if ($subscription_by_user) {

                $additional_information .= 'Failed Plan: ' . $subscription_by_user->meal_plan['plan_name'];

            }

            $this->audit('Failed to Create New Subscription and Billing at Cutover for '.$custom_user->name, 'Failed Creation of a new subscription for '.$custom_user->name . ' and Billing at Cutover.', $additional_information);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createSubscriptionBilling(int $userId)
    {    
        DB::beginTransaction();
        try 
        {   
            $this->setAuth($userId);

            $this->createValidate();

            $this->initApi();
            
            $this->_createBilling();

            $this->setStatusToPaid();

            $this->_createSubscription();

            $this->_createInvoice();

            $this->updateSubscriptionInvoice();

            $this->sessionFacade->destroy();

            DB::commit();

            $custom_user = Users::find($userId);

            $additional_information = '';

            $subscription_by_user = (new SubscriptionRepository)->getByUserIdOderDesc($userId);

            if ($subscription_by_user) {

                $additional_information .= 'Added Plan: ' . $subscription_by_user->meal_plan['plan_name'];

            }

            $this->audit('Created Subscription and Billed it for '.$custom_user->name, 'Created a subscription for '.$custom_user->name . ' and Billed it.', $additional_information);

            $this->infusionsoftCustomerProvider();

            return $this->sendSuccessResponse();
        }
        catch (\Exception $e)
        {
            DB::rollback();
            
            $custom_user = Users::find($userId);

            $additional_information = '';

            $subscription_by_user = (new SubscriptionRepository)->getByUserIdOderDesc($userId);

            if ($subscription_by_user) {

                $additional_information .= 'Failed Plan: ' . $subscription_by_user->meal_plan['plan_name'];

            }

            $this->audit('Failed to Create New Subscription and Billing for '.$custom_user->name, 'Failed Creation of a new subscription for '.$custom_user->name . ' and Billing.', $additional_information);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function _createSubscription()
    {   
        $this->batch->set((new \Configurations)->getActiveBatch());
        $this->discount->setTotal($this->worker->getTotalDiscount());
        $this->discount->setTotalRecur($this->worker->getTotalRecurringDiscount());

        $this->subscriptionFacade->create();
    }

    private function _createBilling()
    {   
        $this->billing = new BillingManager(
            env('MERCHANT_ID'), 
            $this->request->getCardId(), 
            $this->auth->getContactId(),
            $this->worker->getTotalThisWeek(),
            $this->api
        );

        $this->billing->setType('Credit Card');
        $this->billing->setDate(new \DateTime(date('Y-m-d')));    
        $this->billing->setNotes($this->auth->getDeliveryNotes());
        $this->billing->setDescription('Customer order.');

        $products = $this->getProducts();

        $this->worker->applyCouponAsLineItemProvider($products);

        $this->billing->setProducts($products);

        if (
            ($this->worker->total() > 0)
            && ($this->worker->getTotalDiscount() > 0)
            && ($this->worker->getTotalDiscount() >= $this->worker->total())
        ) {
            $this->log->info('Charged invoice was skipped due to discount is greater than or equal to the total billing amount.');
            $this->billing->setSkippedInvoice(true);
        }  

        if(!$this->billing->pay()) {
            throw new \Exception(sprintf(__('billing.failedToBilled'),'subscription. Please try again'), 1);
            
        }
    }

    private function _createInvoice()
    {   
        $this->createInvoice(
            $this->billing->getOrderId(),
            $this->billing->getInvoiceId(),
            $this->worker->getTotalThisWeek()
        );
    }

    public function updateSubscriptionInvoice()
    {   
        $this->subscriptionFacade->updateInvoice($this->billing->getInvoiceId());
    }

    private function setStatusToPaid()
    {
        $this->subscriptionFacade->cyclestatus = 'paid';
    }

    private function createInvoice($orderId, $invoiceId, $total)
    {
        $this->invoice->store([
            'user_id' => $this->auth->getId(),
            'ins_invoice_id' => $orderId,
            'ins_order_id' => $invoiceId,
            'price' => $total,
            'status' => 'paid'
        ]);
    }

    private function getProducts()
    {   
        $products = [];
        foreach($this->order->get() as $row) {
            $row = is_array($row) ? (object)$row : $row;
            $products[] = [
                'infusionsoftProductId' => $row->infusion_product_id, 
                'itemType' => $row->item_type ?? (int)env('PRODUCT_ITEMTYPE'), 
                'price' => $row->price, 
                'quantity' => $row->quantity, 
                'description' => $row->name, 
                'notes' => $row->notes ?? ''
            ];
        }
        return $products;
    }

    private function setAuth(int $userId)
    {
        $this->auth->set($userId);
    }

    private function validateCoupons()
    {
        $factory = new Factory($this->auth->getId());

        $this->validator = new Validator;

        $this->validator->validate([
            'coupon_code' => $this->request->getPromoCode()
        ], [
            'coupon_code' => [$factory->subscription()]
        ]);
        
        if (!$this->validator->isValid()) {
            throw new \Exception($this->validator->filterError($this->validator->getMessage()), 1);
        }
    }

    private function createValidate()
    {   
        // Validate Customer Details and Address if they exist
        $customerFactory = new CustomerValidatorFactory($this->auth->getId());
        $customerFactory->account();

        if (empty($this->order->get())) {
            throw new \Exception(__('There is no plan selected.'), 1);
        }
        
        if (($this->worker->total() == 0) && ($this->worker->getTotalDiscount() == 0)) {
            throw new \Exception(__('billing.noOrderAmountZero'), 1);
        } 
    }
    
    protected function storeOnCouponStorage(int $userId)
    {
        try 
        {   
            $this->setAuth($userId);
            $this->validateCoupons();

            $this->sessionFacade->store();

            return ['success' => true];
        }
        catch (\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function sendSuccessResponse()
    {
        return [
            'success' => true,
            'message' => sprintf(__('crud.created'),'New Plan')
        ];
    }
    
    protected function initApi()
    {
        $this->api = (new InfusionsoftFactory('oauth2'))->service();
    }
}


