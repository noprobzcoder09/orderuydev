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
use App\Services\Reports\Criteria\Kitchen\ByLastCycle;
use App\Services\Reports\Criteria\Kitchen\ByCurrentCycle;
use App\Services\Reports\Criteria\kitchen\ByPreviousCycle;
use App\Services\Reports\Criteria\Kitchen\WithTiming;
use App\Services\Reports\Criteria\Kitchen\WithMeals;
use App\Services\Reports\Criteria\Kitchen\Fields;
use App\Services\Reports\Criteria\Kitchen\WithPaid;
use App\Services\Reports\Criteria\Kitchen\WithBillingIssue;
use App\Services\Reports\Criteria\kitchen\WithMealPlan;


class KitchenMealSplitReport extends BaseReports implements FromView, ReportsInterface
{	 
    use Parameters, Criteria;

	public function __construct($request)
	{   
        $this->request = $request;
		$this->customer = new \App\Repository\CustomerRepository;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {  
       
         return view('exports.kitchen-split', [
            'meals' => $this->data()
        ]);
    }


    public function data()
    {   
        if ($this->isCurrentCycle()) {
            return $this->createCriteria(
                $this->currentCycle()
            );
        }
        else {
            if ($this->isLastCycle()) {

                return $this->createCriteria(
                    $this->lastCycle()
                );

            } else {
                return $this->createCriteria(
                    $this->previousCycle()
                );
            }
        }
    }

    private function createCriteria($model)
    {
        $model = $this->applyCriteria($model, new WithTiming($this->request->getTiming()));
        $model = $this->applyCriteria($model,new Fields);
        // $model = $this->applyCriteria($model,new WithMeals);
        $model = $this->applyCriteria($model,new WithMealPlan);
        
        return $model;
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
