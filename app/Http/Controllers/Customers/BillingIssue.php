<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Services\Customers\BillingIssue\Data;
use App\Services\Customers\BillingIssue\User;
use App\Services\Customers\BillingIssue\Provider;
use App\Services\Customers\BillingIssue\Extended\Request;

use App\Repository\CustomerRepository;
use App\Services\Customer;
use \App\Services\Log;
use Auth;

class BillingIssue extends Controller
{   
    use Provider;
    
    const view = 'pages.customers.billing-issue.';
    const masterlistUrl = 'customers/billing-issue/list';
    const cardModalUrl = 'customers/billing-issue/card-content';
    const addNewCreditCardUrl = 'customers/billing-issue/addnew-creditcard';
    const updateCardDefaultUrl = 'customers/billing-issue/update-creditcard-default';
    const billNowUrl = 'customers/billing-issue/billnow';
    const cancelForTheWeekUrl = 'customers/billing-issue/cancelfortheweek';
    const cancelSubscriptionUrl = 'customers/billing-issue/cancelsubscription';
    
    
    

    public function __construct()
    {   
        $this->data = new Data;
        $this->request = new Request;

        $this->customerRepo = new CustomerRepository;
        $this->customer = new Customer($this->customerRepo);

        $this->log = new Log('admin billing issue','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }    


    /**
     * Show's the application default page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): string
    {        
        return view(self::view.'index')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'masterlistUrl' => self::masterlistUrl,
            'cardModalUrl'  => self::cardModalUrl,
            'addNewCreditCardUrl' => self::addNewCreditCardUrl,
            'updateCardDefaultUrl' => self::updateCardDefaultUrl,
            'billNowUrl' => self::billNowUrl,
            'cancelForTheWeekUrl' => self::cancelForTheWeekUrl,
            'cancelSubscriptionUrl' => self::cancelSubscriptionUrl,
            'previousCycles' => $this->data->getPreviousDeliveryCycle()
        ]);
    }

    /**
     * Handle a masterlist request to the application
     *
     * @return string
     */
    public function list(): string
    {   
        return view(self::view.'table')->with([
            'data' => $this->data->get($this->request->getCycleId()),
            'status' => $this->request->getStatus()
        ]);
    }

    /**
     * Handle a card modal content request to the application
     *
     * @return string
     */
    public function cardContent(int $userId): array
    {   
        if (empty($userId)) {
            throw new \Exception("Customer not found.", 1);
            
        }
        $data = view(self::view.'card-container')->with([
            'userId' => $userId,
            'cardList' => $this->cardList($userId),
            'view' => self::view
        ])->render();

        return [
            'success' => true, 
            'content' => $data
        ];
    }

}



