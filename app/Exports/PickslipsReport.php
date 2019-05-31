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
use App\Services\Reports\Criteria\Pickslips\ByLastCycle;
use App\Services\Reports\Criteria\Pickslips\ByCurrentCycle;
use App\Services\Reports\Criteria\Pickslips\ByPreviousCycle;
use App\Services\Reports\Criteria\Pickslips\WithTiming;
use App\Services\Reports\Criteria\Pickslips\WithMeals;
use App\Services\Reports\Criteria\Pickslips\WithPlans;
use App\Services\Reports\Criteria\Pickslips\WithUserAddress;
use App\Services\Reports\Criteria\Pickslips\Fields;
use App\Services\Reports\Criteria\Pickslips\ByLocation;

use App\Services\Reports\Format\BorderPickslips;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

use \Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class PickslipsReport extends BaseReports implements FromView, ReportsInterface, WithEvents
{	 
    use Parameters, Criteria;

	public function __construct($location, $request, $locationName)
	{  
        $this->location = $location;
        $this->locationName = $locationName;
        $this->request = $request;
        $this->customer = new \App\Repository\CustomerRepository;
       
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {   
        return view('exports.pickslips', $this->data());
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {   
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {

            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        $data = $this->data();
        
        return [
            AfterSheet::class  => function(AfterSheet $event) use ($data) {
                $range = new BorderPickslips($data);

                foreach($range->getColumns() as $r) {
                    $event->sheet->styleCells(
                        $r,
                        [   
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => '00000'],
                                ],
                            ]
                        ]
                    );
                }
            }
        ];
    }

    public function data()
    {   
        if (!empty($this->data)) {
            return $this->data;
        }
        if ($this->isCurrentCycle()) {
            $this->data = $this->createCriteria(
                $this->currentCycle()
            );
        }
        else {
            if ($this->isLastCycle()) {

                $this->data =$this->createCriteria(
                    $this->lastCycle()
                );

            } else {
                $this->data =$this->createCriteria(
                    $this->previousCycle()
                );
            }
        }

        $this->data = array_merge($this->data, ['locationName' => $this->locationName]);
        return $this->data;
    }

    private function createCriteria($model)
    {
        $model = $this->applyCriteria($model, new Fields);
        $model = $this->applyCriteria($model, new WithUserAddress);
        $model = $this->applyCriteria($model, new WithTiming($this->request->getTiming()));
        $model = $this->applyCriteria($model, new WithPlans);
        $model = $this->applyCriteria($model, new ByLocation($this->location));
        $model_ = $model;
        $meals = $this->applyCriteria($model, new WithMeals);
        
        return [
            'customers' => $model->get(),
            'meals'     => $meals
        ];
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
