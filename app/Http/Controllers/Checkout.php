<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Log;
use Request;
use Auth;
use App\Services\Billing;
use App\Services\Customers\Checkout\ResponseCodes;
use App\Services\Customers\Checkout\Client;
use App\Services\Customers\Checkout\SessionRegistration;
use App\Services\Customers\Checkout\Traits\CustomerInfo;

class Checkout extends Controller
{	

    use CustomerInfo;

    const deliveryTimeUrl = 'get-deliverytime-byzone';
    const addtoCartUrl = 'order/addtocart';
    const verifyEmailLoginUrl = 'email-verify';
    const loginUrl = 'auth/ajax/login';
    const accountUrl = 'auth/account';
    const logoutUrl = 'auth/ajax/logout';
    const orderSummaryUrl = 'order/summary';
    const checkoutUrl = 'order/checkout';
    const successUrl = 'dashboard?ref=subscribed'; 
    const verifyCouponUrl = 'order/coupon/enter'; 
    const cardsUrl = '/getcards'; 
    const promoInputsUrl = 'order/promo-inputs';
    const deletePromoUrl = 'order/coupon/delete';
    const shipppingUrl = 'order/shipping/';
    const removeOrderPlanUrl = 'order/remove/';
    const signupUrl = 'order/signup';
    const registerSessionUrl = 'registration/save-session-register';
    const getDeliveryTimingsCutoffDateTimeByDeliveryZoneTimingsUrl = 'checkout/delivery-timings-cutoff/by-delivery-zone-timings';
    const getDeliveryTimingsCutoffDateTimeByTimingsUrl = 'checkout/delivery-timings-cutoff/by-timings';    

    /**
     * Contains view path 
     *
     * @return var
     */
	const view = 'pages.client.checkoutv2.';

    protected $client;


    public function __construct() {
        $this->zoneTimingsRepository = new \App\Repository\ZTRepository;
        $this->timingsRepository = new \App\Repository\TimingRepository;
    }

 
    /**
     * Show's the application default page
     *
     * @return \Illuminate\Http\Response
     */
    public function plan(string $sku): string
    {   
        $this->client = new Client($sku);    

        $this->client->setPlan($this->client->getPlanIdBySku($sku));

        if ($this->client->isExisting()) {
            abort('404','SKU is not found.');
        }

        $id = $this->client->getId();

        $response = [
            'codes'         => (new ResponseCodes)->get(),
            'view'          => self::view,
            'addtoCartUrl'  => self::addtoCartUrl,
            'checkoutUrl'   => self::checkoutUrl,
            'successUrl'    => self::successUrl,
            'meals'         => $this->client->getMenus(),
            'plan'          => $this->client->getPlan(),
            'sku'           => $sku,
            'id'            => $id
        ];

    	return view(self::view.'product-index')->with($response);
    }

    /**
     * Show's the application default page
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(): string
    {   
        $sessionRegister = new SessionRegistration;

        $response = $this->getCheckoutResponse();

        $response = array_merge(
                        $response, 
                        [
                            'getDeliveryTimingsCutoffDateTimeByDeliveryZoneTimingsUrl' => self::getDeliveryTimingsCutoffDateTimeByDeliveryZoneTimingsUrl,
                            'getDeliveryTimingsCutoffDateTimeByTimingsUrl' => self::getDeliveryTimingsCutoffDateTimeByTimingsUrl
                        ]
                    );
       
        if(Auth::check()) {

            $response = array_merge($response, $this->getCustomerAccountInfo());

            return view(self::view.'checkout')->with($response);
        }

        if (!empty($sessionRegister->get())) {

            $response = array_merge($response, $sessionRegister->get());

            return view(self::view.'checkout')->with($response);
        }

        return view(self::view.'register-login-index')->with($response);
    }


    /**
     * Handle the verify coupon request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function promoInputs(): string
    {
        return view(self::view.'promo');
    }

    /**
     * Handle shipping data request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function getShippingData(): string
    {
        return (new \App\Services\Checkout)->deletePromo(Request::get('code'));
    }

    private function getCheckoutResponse()
    {
        $response = [
            'codes' => (new ResponseCodes)->get(),
            'view' => self::view,
            'deliveryTimeUrl' => self::deliveryTimeUrl,
            'verifyEmailLoginUrl' => self::verifyEmailLoginUrl,
            'loginUrl' => self::loginUrl,
            'accountUrl' => self::accountUrl,
            'logoutUrl' => self::logoutUrl,
            'orderSummaryUrl' => self::orderSummaryUrl,
            'checkoutUrl' => self::checkoutUrl,
            'successUrl' => self::successUrl,
            'verifyCouponUrl' => self::verifyCouponUrl,
            'cardsUrl' => self::cardsUrl,
            'promoInputsUrl' => self::promoInputsUrl,
            'deletePromoUrl' => self::deletePromoUrl,
            'shipppingUrl' => self::shipppingUrl,
            'removeOrderPlanUrl' => self::removeOrderPlanUrl,
            'signupUrl' => self::signupUrl,
            'registerSessionUrl' => self::registerSessionUrl
        ];

        return $response;
    }

    public function getDeliveryTimingsCutoff(\Illuminate\Http\Request $request) 
    {

        if (!empty($request->route('delivery_zone_timings_id'))) {
            $zoneTimings = $this->zoneTimingsRepository->get((int)$request->route('delivery_zone_timings_id'));
            $timings = $this->timingsRepository->get((int)$zoneTimings->delivery_timings_id);
        } else {
            $timings = $this->timingsRepository->get((int)$request->route('delivery_timings_id'));
        }
        
        return response()->json([
            'cutoff_time' => date('h:ia', strtotime($timings->cutoff_time)),
            'cutoff_day' => $timings->cutoff_day,
            'delivery_day' => $timings->delivery_day
        ]);
        
                            
    }
    
}
