<?php

namespace App\Services\Customers\Checkout;

use Auth;
use DB;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Traits\Auditable;
use App\Mail\APINotifier;
use App\Models\Configurations;
use Illuminate\Support\Facades\Mail;

Trait Checkout
{   

    use Auditable;

    public function checkout()
    {   
        DB::beginTransaction();
        try
        {   
            $this->setUserStatus(); 
            $this->createValidate();
            $this->initApi();
            $this->createContact($this->getContactWithCompleteFields());
            $this->createCard();
            $this->createBilling();

            $this->createUser();
            $this->updateUserCardAndContactId();
            $this->createSubcsription();
            $this->createInvoice();
            $this->login();
            $this->infusionsoftCustomerUpdate();
            $this->clearSession();

            DB::commit();
            
            $this->audit($title = 'User Placed an Order', $description = 'User has been successfully placed an order.', $additional_data = '', $user_id = NULL);
            return $this->sendSuccessResponse();
        }

        catch (\Exception $e)
        {   
            $config = Configurations::getReportErrorInfsApiAdminEmails();
            $emails = [];
            if ($config) {
                $emails = explode(',', $config);
            }

            if (count($emails) > 0) {
                foreach ($emails as $email) {
                    Mail::to($email)->send(new APINotifier());
                }
            }   
            
            DB::rollback();
            $this->log->error($e->getMessage());

            $this->audit($title = 'User Checkout Failed', $description = 'Upon checking out, the user encountered an error. ', $additional_data = $e->getMessage());
            throw $e;
            return [
                'success' => false,
                'message' => __('config.report-api-error'),
                'code' => $e->getCode(),
            ];
        }
        
    }

    public function saveSessionRegistration()
    {   
        DB::beginTransaction();
        try
        {   
            // Removed session
            /*$this->sessionRegister->store(
                $this->request->getEmail(),
                $this->request->getPassword(),
                $this->request->getConfirmPassword()
            );*/

            $this->validateUserEmailForRegistration();

            $this->initApi();
            $this->createContact($this->getContactWithNameOnly());
            $this->createAccountWithoutName();
            $this->login();

            DB::commit();

            return \Helper::success(__('users.account_created'));
        }
        catch (\Exception $e) {
            DB::rollback();
            return \Helper::failed($e->getMessage());
        }
    }

    public function getOrderSummary()
    {   
        return view(self::view.'order-summary',[
            'worker'    => $this->worker,
            'order'     => $this->order->get(),
            'coupons'   => $this->coupon->get(),
        ]);
    }


    public function addtocart()
    {   
        $this->order->store(
            $this->request->getPlanId(), 
            $this->request->getMeals()
        );

        $additional_data = [
            'Plan ID' => $this->request->getPlanId(),
            'Meal IDs' => $this->request->getMeals()
        ];
        $this->audit($title = 'User Cart Items Added', $description = 'User added an items into their cart. ', json_encode($additional_data));
        return \Helper::success('',['checkoutUrl' => $this->getCheckoutUrl()]);
    }

    public function removePlan(int $planId)
    {  
        $this->order->delete(
            $planId
        );

        if (count($this->order->get()) <= 0) {
            $this->coupon->destroy();
        }
       
        return 1;
    }
    

    private function getProducts()
    {   
        $products = [];
        $itemType = (int)env('PRODUCT_ITEMTYPE');
        foreach($this->order->get() as $row) {
            $row = is_array($row) ? (object)$row : $row;
            $products[] = [
                'infusionsoftProductId' => $row->infusion_product_id, 
                'itemType' => $row->item_type ?? $itemType, 
                'price' => $row->price, 
                'quantity' => $row->quantity, 
                'description' => $row->name, 
                'notes' => $row->notes ?? ''
            ];
        }
        
        $this->worker->applyCouponAsLineItemProvider($products);

        return $products;
    }

    private function clearSession()
    {
        $this->order->destroy();
        $this->coupon->destroy();
        $this->sessionRegister->destroy();
    }

    private function createValidate()
    {
        if (empty($this->order->get())) {
            throw new \Exception(__('There is no plan selected.'), 1);
        }

        if (empty($this->order->getMeals($this->order->getPlanId()))) {
            throw new \Exception(__('There is no meals selected.'), 1);
        }

        if (($this->worker->total() == 0) && ($this->worker->getTotalDiscount() == 0)) {
            throw new \Exception(__('billing.noOrderAmountZero'), 1);
        } 

        $this->authValidator();

    }

    public function authValidator()
    {
        if ($this->auth()) {
            if (!Auth::check()) {
                throw new \Exception(__('auth.expired'), __('codes.authExpired'));
            }
        }

        if ($this->user->new()) {
            $this->user->validate($this->request->getUser());
        }

        return true;
    }

    public function validateUserEmailForRegistration()
    {
        if (empty($this->request->getPassword()) || empty($this->request->getConfirmPassword())) {
            throw new \Exception(__('passwords.empty'));
        }
        if($this->request->getPassword() != $this->request->getConfirmPassword()) {
            throw new \Exception(__('passwords.not_match'));
        }
        if (! empty($this->user->getIdByEmail($this->request->getEmail()))) {
            throw new \Exception(__('users.user_email_taken_user_registration'));
        }
    }

    protected function auth(): bool {
        return $this->request->auth() == 1 || $this->request->auth() === true;
    }

    protected function initApi()
    {
        $this->api = (new InfusionsoftFactory('oauth2'))->service();
    }
}



