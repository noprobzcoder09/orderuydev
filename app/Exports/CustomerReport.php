<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Exports\BaseReports;
use App\Exports\ReportsInterface;
use App\Services\Reports\Types;
use App\Services\Reports\Parameters;
use App\Services\Reports\Criteria;
use App\Services\Reports\Criteria\WithContactId;
use App\Services\Reports\Criteria\Customer\ByLastCycle;
use App\Services\Reports\Criteria\Customer\ByCurrentCycle;
use App\Services\Reports\Criteria\Customer\ByPreviousCycle;
use App\Services\Reports\Criteria\Customer\WithTiming;
use App\Services\Reports\Criteria\Customer\GroupUser;
use App\Services\Reports\Criteria\Customer\ByLocation;
use App\Services\Reports\Criteria\Customer\Fields;
use App\Services\Reports\Criteria\Customer\WithPlans;
use App\Services\Reports\Criteria\Customer\UserDetails;
use App\Services\Reports\Criteria\Customer\UserDeliveryZone;


class CustomerReport extends BaseReports implements FromView, ReportsInterface
{	 
    use Parameters, Criteria;

	private $contactUrl = 'Contact/manageContact.jsp';
    private $location;

	public function __construct($location = '', $request)
	{  
        $this->location = $location;
        $this->request = $request;
		$this->customer = new \App\Repository\CustomerRepository;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {   
       
       // dd($this->customerPlans);
        return view('exports.customer', [
            'customers' => $this->data(),
            'customerPlans' => $this->customerPlans,
            'insfsUrl' => $this->getInfsUrl().$this->contactUrl
        ]);
    }


    public function data()
    {   
        if ($this->isCurrentCycle()) {
            return $this->createCriteria(
                $this->currentCycle()
            )->get();
        }
        else {
            if ($this->isLastCycle()) {

                return $this->createCriteria(
                    $this->lastCycle()
                )->get();

            } else {
                return $this->createCriteria(
                    $this->previousCycle()
                )->get();
            }
        }
    }

    private function createCriteria($model)
    {   
        $model = $this->applyCriteria($model, new Fields);
        $model = $this->applyCriteria($model, new WithTiming($this->request->getTiming()));
        $model = $this->applyCriteria($model, new GroupUser);
        $model = $this->applyCriteria($model, new ByLocation($this->location));

        $model = $this->applyCriteria($model, new UserDeliveryZone);

        $this->customerPlans = new WithPlans($model);
        $this->customerPlans = $this->customerPlans->getCustomerPlans();

        return $model;
    }

    private function getInfsUrl()
    {
        $env = strtolower(env('APP_ENV'));
        if ($env == 'live') {
            return env('LIVE_INFS_APP_URL');
        }
        return env('INFS_APP_URL');
    }

    public function withContactId()
    {
        return new WithContactId;
    }  

    public function byLastCycleInstance()
    {
        return new ByLastCycle($this->request->getTiming());
    }

    public function byCurrentCycleInstance()
    {
        return new ByCurrentCycle();
    }

    public function byPreviousCycleInstance()
    {
        return new ByPreviousCycle(
            $this->getPreviousCycle()
        );
    }

    public function base()
    {   
        $all = $this->customer->all();
        if (strtolower(env('APP_ENV')) == 'live') 
            $all = $this->customer->AllCustomer();

        return $this->applyCriteria($all,new WithContactId);
    }
}
