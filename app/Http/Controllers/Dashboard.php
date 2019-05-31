<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as AuthGuard;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\InfusionsoftV2\Tag;
use App\Services\Dashboard\Config;
use Configurations as Configuration;
use Log;
use Auth;
use Request;
use App\Traits\Auditable;

class Dashboard extends Controller
{	

    use Auditable;
    /**
     * Contains view path 
     *
     * @return var
     */
	const view = 'pages.client.dashboard.';

    /**
     * Contains view path 
     *
     * @return var
     */
    const viewBillingIssue = 'pages.client.dashboard.billing-issue.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
    {
        $this->config = new Config;
    }
    
    /**
     * Show's the application default page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): string
    {   
        $response = array();

        $repository = new \App\Repository\CustomerRepository;
        $this->services = new \App\Services\Dashboard(Auth::id());

        if ($this->services->isUserDeliveryZoneRemoved(Auth::id())) {
            $response = array_merge($response, $this->config->getSetupDeliveryZoneUrls());
            $response['view'] = self::view;
            return view(self::view.'setup-delivery-zone')->with($response);
        }

        if ($this->services->isUserHasBillingissue(Auth::id())) {
            $response = array(
                'unpaidSubscriptions' => $this->services->getUnpaidSubscriptionsToArray(Auth::id()),
                'forDeliverySubscriptions' => $this->services->getForDeliverySubscriptionsToArray(Auth::id()),
                'view' => self::viewBillingIssue
            );
            $response = array_merge($response, $this->config->getBillingIssuePageUrl());
            
            return view(self::view.'billing-issue')->with($response);
        }

        $response = [
            'view' => self::view,
            'myPlansIdOnly' => $this->services->myPlansIdOnly(),
            'myPlans' => $this->services->myPlans(),
            'mealPlans'    => $this->services->mealPlans(),
            'manageSubscriptionText' => Configuration::getManageSubscriptionText()
        ];

        $response = array_merge($response, $this->config->getConfigIndexUrls());
        $response = array_merge($response, $repository->getAccount(Auth::id()));

    	return view(self::view.'index')->with($response);
    }

    public function getDeliveryZoneTimings(int $zoneId) {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->getDeliveryZoneTimings($zoneId);
    }


    public function getNextDateDelivery(int $DZtimingId) {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->getNextDateDelivery($DZtimingId);
    }

    public function getPlans()
    {   
        $this->services = new \App\Services\Dashboard(Auth::id());
        return view(self::view.'manage-plans.listings')->with(['plans' => $this->services->getPlans()]);
    }

    public function previousWeeksSubscription(){

        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  $customer->weeksSubcriptions(self::view, 
            Auth::user()->id, (int)Request::get('subid'), ['pending','unpaid','paused']
        );
    }
    
    public function getInvoices() {
        
        $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
        return  ['data' => $customer->getInvoicesSubcriptions(Auth::user()->id)];
    }

    public function cancellAllPlans()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->cancellAllPlans();
    }

    public function cancellPlan()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->cancellPlan(Request::get('subscriptionId'), Request::get('subscriptionCycleId'));
    }

    public function saveStopTillDate()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->saveStopTillDate(Request::get('subscriptionId'), Request::get('subscriptionCycleId'), new \DateTime(Request::get('date')));
    }

    public function saveStopAllTillDate()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->saveStopAllTillDate(new \DateTime(Request::get('date')));
    }
    

    public function cancelPausedDate()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->cancelPausedDate(Request::get('subscriptionId'), Request::get('subscriptionCycleId'));
    }

    public function saveCard()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->addCard(Request::all());
    }

    public function updateBillingInfo()
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->updateBillingInfo(Request::all());
    }

    public function saveSelections()
    {   
        try 
        {   
            $dataStorage = array();
            $dataStorage['saved'] = array();
            $total_paused = 0;
            foreach(Request::get('data') as $row) {

                $is_paused = (new \App\Models\Subscriptions)->find($row['subscriptionId']);

                if ($is_paused->status !== 'paused') {
                    
                    $this->services = new \App\Services\Dashboard\Menu(Auth::id(), $row['subscriptionId'], $row['subscriptionCycleId']);
                    $this->services->mealLunch = $row['menu']['lunch'];
                    $this->services->mealDinner = $row['menu']['dinner'];
                    $this->services->saveSelections();

                    $saved_changes   = (new \App\Repository\SubscriptionRepository)->getMealPlanBySubscriptionId($row['subscriptionId']);

                    $this->audit('User Made Menu Changes', 'The user has made changes on ' . $saved_changes['meal_plan']['plan_name'] . ' menu.', '');

                }else{
                    $total_paused += 1;
                }
            }

            $tag = new Tag;
            $customer = new \App\Services\Customer(new \App\Repository\CustomerRepository);
            $infusionsoftCustomer = new InfusionsoftCustomer(Auth::id());

            $infusionsoftCustomer->updateCustomerInfs();
            $infusionsoftCustomer->savedTagToContact(
                $tag->getMenuSavedId(), [$customer->getINSContactId(Auth::id())]
            );

            if ($total_paused > 0) {
                return [
                    'success'   => true,
                    'code'      => __('code.ok'),
                    'message'   => $total_paused . ' Subscriptions(s) is currently paused, you cannot update their meals. But the other active subscriptions has been succesfully updated.'
                ];
            }


            return [
                'success'   => true,
                'code'      => __('code.ok'),
                'message'   => 'Successfully Saved!'
            ];
        }        
        catch (\Exception $e) 
        {   
            return [
                'success'   => false,
                'code'      => __('codes.generalError'),
                'message'   => $e->getMessage()
            ];
        }
    }

    public function getSubscriptionIds()
    {   
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->getSubscriptionIds();
    }

    public function getMenuPage()
    {
        $this->services = new \App\Services\Dashboard\Menu(Auth::id(),  Request::get('subscriptionId'), Request::get('subscriptionCycleId'));
        if ($this->services->isSubscribed()) {
            return view(self::view.'menu-page', array_merge([
                'view' => self::view, 
                'subscriptionId' => Request::get('subscriptionId'),
                'subscriptionCycleId' => Request::get('subscriptionCycleId'),
            ], 
                $this->services->selections())
            );
        }
        return view(self::view.'selections.create-plan');
    }

    /**
     * Handle a getting delivery timing schedule request to the application
     *
     * @return string
     */
    public function futureDeliveryTimingSchedule(): string
    {   
        $this->services = new \App\Services\Dashboard(Auth::id());
        return view(self::view.'manage-plans.date',[
            'dates' => $this->services->getFutureDeliveryTimingSchedule(Request::get('subscriptionCycleId')),
            'subscriptionId' => Request::get('subscriptionId'),
            'subscriptionCycleId' => Request::get('subscriptionCycleId'),
            'deliveryDate' => Request::get('deliveryDate'),
            'subscriptionCycleStatus' => Request::get('subscriptionCycleStatus')
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(\Illuminate\Http\Request $request)
    {   
        AuthGuard::guard()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }


    public function getDeliveryTimingsSettings() 
    {
        $this->services = new \App\Services\Dashboard(Auth::id());
        return $this->services->getDeliveryTimingsSettings(Auth::id());
    }
    
    
    
}

