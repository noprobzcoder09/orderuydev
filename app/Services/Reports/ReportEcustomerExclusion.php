<?php

namespace App\Services\Reports;

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

use App\Services\Reports\Parameters;

Class ReportEcustomerExclusion
{     
    use Criteria;
    use Parameters;

    public function __construct($request)
    {  
        $this->request = $request;
        $this->customer = new \App\Repository\CustomerRepository;
    }

    public function isLocationHaveCustomer(int $locationId)
    {   
        $this->location = $locationId;
        
        $data = $this->locationData();
        return $data > 0;
    }

    private function createCriteriaLocation($model)
    {   
        $model = $this->applyCriteria($model, new Fields);
        $model = $this->applyCriteria($model, new WithTiming($this->request->getTiming()));
        $model = $this->applyCriteria($model, new GroupUser);
        $model = $this->applyCriteria($model, new ByLocation($this->location));

        return $model;
    }

    private function locationData()
    {   
        if ($this->isCurrentCycle()) {
            return $this->createCriteriaLocation(
                $this->currentCycle()
            )->limit(1)->count();
        }
        else{
            if ($this->isLastCycle()) {
                return $this->createCriteriaLocation(
                    $this->lastCycle()
                )->limit(1)->count();
            } else {
                return $this->createCriteriaLocation(
                    $this->previousCycle()
                )->limit(1)->count();
            }
        }
    }

    protected function isCurrentCycle()
    {
        return ($this->getCurrentCycle() == $this->request->getParameter());
    }

    protected function isLastCycle() 
    {   
        return ($this->getLastCycle() == $this->request->getParameter());
    }

    private function currentCycle()
    {
        return $this->applyCriteria($this->base(), $this->byCurrentCycleInstance());
    }

    private function lastCycle()
    {
        return $this->applyCriteria($this->base(), $this->byLastCycleInstance());
    }

    private function previousCycle()
    {
        return $this->applyCriteria($this->base(), $this->byPreviousCycleInstance());
    }

    private function byLastCycleInstance()
    {
        return new ByLastCycle($this->request->getTiming());
    }

    private function byCurrentCycleInstance()
    {
        return new ByCurrentCycle();
    }

    private function byPreviousCycleInstance()
    {
        return new ByPreviousCycle(
            $this->request->getParameter()
        );
    }

    private function base()
    {   
        $all = $this->customer->all();

        return $this->applyCriteria($all,new WithContactId);
    }
}

