<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Request;

class Customers extends Controller
{	

    use SendsPasswordResetEmails;
    /*
    |--------------------------------------------------------------------------
    | Customers Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a Class Services for assisting the application events and actions
    |
    */

    /**
     * Contains view path 
     *
     * @return var
     */
	const view = 'pages.customers.';

    /**
     * Contains view invoice path
     *
     * @return var
     */
    const invoice = 'pages.customers.invoice.';

    /**
     * Contains customers url
     *
     * @return var
     */
	const url = 'customers/';

    /**
     * Contains new page url
     *
     * @return var
     */
    const urlNew = 'customers/new/';

    /**
     * Contains invoice url
     *
     * @return var
     */
    const invoiceUrl = 'customers/invoice/';

    /**
     * Contains search url
     *
     * @return var
     */
    const searchUrl = 'customers/search/';

    /**
     * Contains create url
     *
     * @return var
     */
    const createUrl = 'customers/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'customers/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'customers/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'customers/delete/';

    /**
     * Contains verify email url
     *
     * @return var
     */
    const verifyEmailUrl = 'customers/verify-email';

    /**
     * Contains update customer profile url
     *
     * @return var
     */
    const updateCustomerProfileUrl = 'customers/update-profile/';

    /**
     * Contains update customer delivery timing zone url
     *
     * @return var
     */
    const updateCustomerDeliveryUrl = 'customers/update-delivery/';

    /**
     * Contains create new subscription url
     *
     * @return var
     */
    const createSubscriptionUrl = 'customers/create-subscription/';
    const createdCardUrl = 'customers/create-card/';

    /**
     * Contains create new subscription url
     *
     * @return var
     */
    const masterlistUrl = 'customers/list';

    const cancelUrl = 'customers/cancel-subscription';
    const resetPasswordUrl = 'customers/reset-password';
    const pauseUrl = 'customers/pause-subscription';
    const playUrl = 'customers/play-subscription';
    const futureDeliveryTimingScheduleUrl = 'customers/future-delivery-timing-schedule';
    const deliveryTimeUrl = 'customers/get-deliverytime-byzone';
    const saveNewPlanUrl = 'customers/new-plan';
    const saveNewPlanUrlWithBilling = 'customers/new-plan-with-billing';
    const subscriptionIdsUrl = 'customers/subscriptionids';
    const storeCouponUrl = 'customers/store-coupon';
    const orderSubscriptionSummaryUrl = 'customers/order-subscription-summary';
    const removeCouponUrl = 'customers/remove-coupon';
    const updatePlanUrl = 'customers/updateplan';
    const updateStatusUrl = 'customers/updatestatus/';
    const addMenuPrevWeekContentUrl  = 'customers/addmenuprevweekcontent';
    const addMenuPrevWeekOrderSubscriptionSummaryUrl = 'customers/addmenuprevweekordersummary';
    const addMenuPrevWeekOrderUpdatePlanUrl = 'customers/addmenuprevweekorderupdateplan';
    const addMenuPrevWeekOrderUrl = 'customers/new-plan-previous-week';
    const addMenuPrevWeekOrderWithBillingUrl = 'customers/new-plan-with-billing-previous-week';
    const pastMenusPreviousSubscriptionsUrl = 'customers/previous-menu-selections';
    const updatePreviousSubscriptionsUrl = 'customers/previous-menu-selections/update';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new \App\Services\Customer(new \App\Repository\CustomerRepository);
    }

    /**
     * Show's the application default page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): string
    {        
    	return view(self::view.'index')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
    		'url'			=> self::url,
            'masterlistUrl' => self::masterlistUrl
    	]);
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function new(): string
    {   
        if ($this->services->findEmail(Request::get('email'))) {
            return $this->view($this->services->getIdByEmail(Request::get('email')));
        }

        return view(self::view.'new')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'url'           => self::url,
            'edit'          => false,
            'actionUrl'     => self::createUrl,
            'verifyEmailUrl' => self::verifyEmailUrl,
            'editUrl'       => self::editUrl,
            'zoneTimingList'    => $this->services->zoneTimingList()
        ]);
    }

    /**
     * Show's the application find email page
     *
     * @return \Illuminate\Http\Response
     */
    public function findEmail(): string
    {
        return view(self::view.'email-checker')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'url'           => self::url,
            'search'        => self::searchUrl
        ]);
    }
    
    /**
     * Show's the application edit page
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function view($id): string
    {   
        if (empty($this->services->repository->account($id)))
                throw new \Exception("No Customer Record Found!", 1);
                
        $data = [
            'breadcrumb'    => $this->breadcrumb('last'),
            'view'          => self::view,
            'invoiceUrl'    => self::invoiceUrl,
            'urlNew'        => self::urlNew,
            'id'            => $id,
            'edit'          => true,
            'ins_contact_id'=> $this->services->getINSContactId($id),
            'updateCustomerProfileUrl'  => self::updateCustomerProfileUrl,
            'updateCustomerDeliveryUrl' => self::updateCustomerDeliveryUrl,
            'createSubscriptionUrl'     => self::createSubscriptionUrl,
            'cancelUrl'                 => self::cancelUrl,
            'resetPasswordUrl'          => self::resetPasswordUrl,
            'pauseUrl'                  => self::pauseUrl,
            'playUrl'                   => self::playUrl,
            'createdCardUrl'            => self::createdCardUrl,
            'deliveryTimeUrl'           => self::deliveryTimeUrl,
            'futureDeliveryTimingScheduleUrl'    => self::futureDeliveryTimingScheduleUrl,
            'saveNewPlanUrl'     => self::saveNewPlanUrl,
            'saveNewPlanUrlWithBilling' => self::saveNewPlanUrlWithBilling,
            'subscriptionIdsUrl'        => self::subscriptionIdsUrl,
            'storeCouponUrl'            => self::storeCouponUrl,
            'orderSubscriptionSummaryUrl' => self::orderSubscriptionSummaryUrl,
            'removeCouponUrl'           => self::removeCouponUrl,
            'updatePlanUrl'             => self::updatePlanUrl,
            'updateStatusUrl'           => self::updateStatusUrl,
            'addMenuPrevWeekContentUrl' => self::addMenuPrevWeekContentUrl,
            'addMenuPrevWeekOrderSubscriptionSummaryUrl' => self::addMenuPrevWeekOrderSubscriptionSummaryUrl,
            'addMenuPrevWeekOrderUpdatePlanUrl' => self::addMenuPrevWeekOrderUpdatePlanUrl,
            'addMenuPrevWeekOrderUrl'   => self::addMenuPrevWeekOrderUrl,
            'addMenuPrevWeekOrderWithBillingUrl' => self::addMenuPrevWeekOrderWithBillingUrl,
            'pastMenusPreviousSubscriptionsUrl' => self::pastMenusPreviousSubscriptionsUrl,
            'updatePreviousSubscriptionsUrl' => self::updatePreviousSubscriptionsUrl
        ];
        
        $data = array_merge($data, [
            'profile' => $this->services->profile($id),
            'address' => $this->services->address($id),
            'account' => $this->services->account($id),
            'zoneTiming' => $this->services->zoneTiming($id),
            'zoneTimingList'    => $this->services->zoneTimingList(),
            'mealPlans'    => $this->services->mealPlans(),
            'coupons'   => $this->services->getActiveCoupons(new \App\Repository\CouponsRepository)
        ]);

        $data = array_merge($data, $this->services->getDeliveryTimingsId($id));

    	return view(self::view.'view')->with($data);
    }

    /**
     * Show's the application invoice page
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice($id): string
    {
        return view(self::invoice.'index')->with([
            'breadcrumb'    => $this->breadcrumb('last'),
            'view'          => self::invoice,
            'id'            => $id
        ]);
    }

    /**
     * Handle the list of active subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function activeSubcriptions(): array
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return ['data' => $customer->getActiveSubcriptions(Request::get('user_id'))];
    }

    /**
     * Handle the list of active subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function pastSubcriptions(): array
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return ['data' => $customer->getPastSubcriptions(Request::get('user_id'))];
    }

    /**
     * Handle the list of weeks subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function weeksSubcriptions(): string
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  $customer->weeksSubcriptions(self::view, 
            (int)Request::get('user_id'), (int)Request::get('subid')
        );
    }

    /**
     * Handle the list of weeks subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function pastWeeksSubcriptions(): string
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  $customer->pastWeeksSubcriptions(self::view, 
            (int)Request::get('user_id'), (int)Request::get('subid')
        );
    }

    /**
     * Handle the list of weeks subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceWeeksSubscriptions(): string
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  $customer->getInvoiceMenuSubscriptions(self::view, (int)Request::get('subcycleid'));
    }

    /**
     * Handle the list of menus from weeks subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function menusWeekSubcriptions(): string
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  $customer->getMenuWeekSubcriptions(self::view, Request::get('id'));
    }

    /**
     * Handle the list of menus from weeks subscription request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function pastMenusWeekSubcriptions(): string
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  $customer->getPastMenuWeekSubcriptions(self::view, Request::get('id'));
    }

    /**
     * Handle the list of invoice request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicesSubcriptions()
    {
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  ['data' => $customer->getInvoicesSubcriptions(Request::get('userId'))];
    }

    /**
     * Handle the search request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function search(): array
    {   
        return  $this->services->getEmailSearchResult(Request::get('email'));
    }

    /**
     * Handle the verify email request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail(): string
    {
        return $this->services->verify(Request::get('email'));
    }
    
    /**
     * Handle a create customer request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(): array
    {   
        return $this->services->store(Request::all());
    }

    /**
     * Handle a create subscription request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createSubscription(): array
    {   
        return $this->services->storeSubscription(Request::all());
    }

    /**
     * Handle a update customer request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(): array
    {
        return $this->services->update(Request::all());
    }

    /**
     * Handle a update profile request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(): array
    {
        $response = $this->services->update(Request::all());
        $response = array_merge($response, [
            'html' => view(self::view.'table-customer',[
                'profile' => $this->services->profile(Request::get('id')),
                'account' => $this->services->account(Request::get('id')),
                'address' => $this->services->address(Request::get('id')),
            ])->render()
        ]);
        return $response;
    }

    /**
     * Handle a update delivery request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateDelivery(): array
    {
        try
        {
            $response = $this->services->updateDelivery(Request::all());

            $response = array_merge($response, [
                'html' => view(self::view.'table-delivery',[
                    'zoneTiming' => $this->services->zoneTiming(Request::get('id')),
                    'zoneTimingList'    => $this->services->zoneTimingList(),
                ])->render()
            ]);
            return $response;
        }
        catch(\App\Exceptions\UpdateDeliveryNoSubscription $e) {
            $response = \Helper::success(sprintf(__('crud.updated'),' Delivery Zone Timing'));
            $response = array_merge($response, [
                'html' => view(self::view.'table-delivery',[
                    'zoneTiming' => $this->services->zoneTiming(Request::get('id')),
                    'zoneTimingList'    => $this->services->zoneTimingList(),
                ])->render()
            ]);
            return $response;
        }
        catch(\Exception $e) {
            return \Helper::failed($e->getMessage());
        }
    }

    /**
     * Handle a masterlist request to the application
     *
     * @return string
     */
    public function masterlist()
    {   
        return $this->services->getAllByStatus(
            Request::get('status'),
            Request::get('filter_type'),
            Request::get('filter')
        );
    }
    
    /**
     * Handle a cancel request to the application
     *
     * @return string
     */
    public function cancelSubscription(int $userId, int $subscriptionId, int $subscribeCycleId): string
    {
        return $this->services->cancelSubscription($userId, $subscriptionId, $subscribeCycleId);
    }

    /**
     * Handle a pause request to the application
     *
     * @return string
     */
    public function pauseSubscription(int $userId, int $subscriptionCycleId): string
    {   
        $date = new \DateTime(Request::get('date'));
        return $this->services->pauseSubscription($userId, $subscriptionCycleId, $date);
    }

    /**
     * Handle a pause request to the application
     *
     * @return string
     */
    public function playSubscription(int $userId, int $subscriptionCycleId): string
    {
        return $this->services->playSubscription($userId, $subscriptionCycleId);
    }

    /**
     * Handle a getting delivery timing schedule request to the application
     *
     * @return string
     */
    public function futureDeliveryTimingSchedule(int $userId, int $subscriptionCycleId): string
    {
        return view(self::view.'customer.date',['dates' => $this->services->getFutureDeliveryTimingSchedule($userId, $subscriptionCycleId)]);
    }


    /**
     * Handle a update status request to the application
     *
     * @return string
     */
    public function updatestatus(int $userId, int $subscriptionCycleId)
    {
        return $this->services->updatestatus($userId, $subscriptionCycleId, Request::get('status'));
    }
    

    /**
     * Handle a getting delivery timings request to the application
     *
     * @return string
     */
    public function getDeliveryZoneTimings(int $zoneId) {
        return $this->services->getDeliveryZoneTimings($zoneId);
    }

    /**
     * Handle a add new menu for previous week content request to the application
     *
     * @return string
     */
    public function addmenuprevweekcontent(int $userId, int $subscriptionId) {
        try
        {
            $data = view(self::view.'customer.addnewmenupreviousweekcontent', [
                'previous_cycle' => $this->services->getPreviousCycle($userId, $subscriptionId, Request::get('subscriptionCycleId')),
                'previousMealPlan' => $this->services->getPreviousSubscriptionMealPlan($subscriptionId),
                'subscriptionId' => $subscriptionId
            ])->render();

            return ['success' => true, 'message' => $data];
        }
        catch (\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function searchCustomerViaNavigation() {
        return $this->services->getSearchCustomerViaNavigation(
            Request::get('phrase')
        );
    }


    public function resetPassword() {
        
       $customer = new \App\Repository\CustomerRepository;
       $email = $customer->getEmailByUserId(Request::route('userId'));
        
       //send reset link
        $sent = $this->broker()->sendResetLink(
            ['email' => $email]
        );
        
        $result = ($sent == 'passwords.sent') ? true : false;
        return response()->json(['success' => $result]);
    }


    public function loadPreviousMenuSelections() {
        return $this->services->loadPreviousMenuSelections(self::view, Request::route('subscription_cycle_id'));
    }

    public function updatePreviouseMenuSelections() {
        return $this->services->updatePreviouseMenuSelections(Request::route('subscription_cycle_id'), Request::get('menus'));
    }

    
}
