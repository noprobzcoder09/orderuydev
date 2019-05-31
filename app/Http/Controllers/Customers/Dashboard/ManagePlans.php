<?php

namespace App\Http\Controllers\Customers\Dashboard;

use App\Http\Controllers\Controller;

use App\Services\Manageplan\Plan;
use App\Services\Manageplan\SessionFacade;
use App\Services\Manageplan\SubscriptionFacade;
use App\Services\Manageplan\Coupon;
use App\Services\Manageplan\Order;
use App\Services\Manageplan\Worker;
use App\Services\Manageplan\Request;
use App\Services\Manageplan\Batch;
use App\Services\Manageplan\Discount;
use App\Services\Manageplan\Auth as Authenticator;
use App\Services\Coupons\Validator\Factory;
use \App\Services\Validator;
use App\Services\Customers\Account\InfusionsoftCustomer;
use Log;
use Auth;
use DB;
use App\Traits\Auditable;

class ManagePlans extends Controller
{	

    use Auditable;

    const view = 'pages.client.dashboard.manage-plans.';

    public function __construct()
    {	  
        $this->middleware(function($request, $next) {
        	$this->order = new Order;
        	$this->coupon = new Coupon;
        	$this->request = new Request;
            $this->batch = new Batch;
            $this->discount = new Discount;
            $this->plan = new Plan;
            $this->auth = new Authenticator;
            $this->worker = new Worker($this->order, $this->coupon);
        	$this->sessionFacade = new SessionFacade(
                $this->request, $this->auth, $this->order, $this->coupon
            );
            $this->subscriptionFacade = new SubscriptionFacade(
                $this->request, $this->auth, $this->order, 
                $this->coupon, $this->discount, $this->batch
            );

            return $next($request);
        });
    }

    public function getOrderSummary()
    {   
        return view(self::view.'subscription-summary',[
        	'worker' 	=> $this->worker,
        	'order' 	=> $this->order->get(),
        	'coupons' 	=> $this->coupon->get(),
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

    public function createSubscription()
    {   
        DB::beginTransaction();
        try 
        {   
            $this->createValidate();

            $this->auth->setId(Auth::id());
            $this->batch->set((new \Configurations)->getActiveBatch());
            $this->discount->setTotal($this->worker->getTotalDiscount());
            $this->discount->setTotalRecur($this->worker->getTotalRecurringDiscount());

            $this->subscriptionFacade->create();

            $this->sessionFacade->destroy();

            DB::commit();

            $this->infusionsoftCustomerProvider();

            $this->audit($title = 'User Added New Plan', $description = 'User has been added new plan.', $additional_data = '', Auth::id());

            return $this->sendSuccessResponse();
        }
        catch (\Exception $e)
        {   
            DB::rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function validateCoupons()
    {
        $factory = new Factory(Auth::check() ? Auth::id() : 0);

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
        if (empty($this->order->get())) {
            throw new \Exception(__('There is no plan selected.'), 1);
        }

        if ($this->worker->getTotalThisWeek() <= 0) {
            throw new \Exception(__('billing.noOrderAmountZero'), 1);
        }
    }
    
    protected function storeOnCouponStorage()
	{
		try 
		{
			$this->plan->setId($this->request->getPlanId());

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

    private function infusionsoftCustomerProvider()
    {   
        $infusionsoft = new InfusionsoftCustomer($this->auth->getId());
        $infusionsoft->updateCustomerInfs();
    }
	
}


