<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\Cycles;

use App\Rules\Custom;

use DB;

Class CycleRepository
{	
    public $successSavedMessage = 'Successfully created new Cycle Plan.';

    public $successUpdatedMessage = 'Successfully updated Cycle Plan.';

    public $successDeletedMessage = "Successfully deleted Cycle Plan.";

    public $errorDeleteMessage = "Sorry could not delete Cycle Plan.";

    const rules = [
        'store' => [
            'cycle_id'              => 'required',
            'meal_plans_id'         => 'required',
            'default_selections'    => 'required'
        ],

        'edit' => [
            'cycle_id'              => 'required',
            'meal_plans_id'         => 'required',
            'default_selections'    => 'required'
        ],
    ];

    const primary_key = 'id';

    const delivery_timings_id = 'delivery_timings_id';

    const delivery_date = 'delivery_date';

    const cutover_date = 'cutover_date';

    const status = 'status';

    const default_selections = 'default_selections';

    const default_selections_veg = 'default_selections_veg';

    const batch = 'batch';

    public $id;

    public function __construct() 
    {
        $this->model = new Cycles;
    }

    public function store(array $data): array
    {   
        
        $data = $this->model->create([
            self::delivery_timings_id  => $data['delivery_timings_id'],
            self::delivery_date => $data['delivery_date'],
            self::cutover_date => $data['cutover_date'],
            self::default_selections  => $data['default_selections'],
            self::default_selections_veg => $data['default_selections_veg'],
            self::status => $data['status'],
            self::batch => isset($data['batch']) ? $data['batch'] : '',
        ]);

        $this->setId($data->id);

        return (array)$data;
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::delivery_timings_id  => $data['delivery_timings_id'],
            self::delivery_date => $data['delivery_date'],
            self::cutover_date => $data['cutover_date'],
            self::default_selections  => $data['default_selections'],
            self::default_selections_veg => $data['default_selections_veg'],
            self::status => $data['status']
        ]);
    }

    public function updateStatusByTimingId(int $id, int $status): array
    {   
        return
        (array)$this->model->where(self::delivery_timings_id, $id)
        ->update([
            self::status  => $status,
        ]);
    }

    public function updateStatusById(int $cycleId, int $status): array
    {   
        return
        (array)$this->model->where(self::primary_key, $cycleId)
        ->update([
            self::status  => $status,
        ]);
    }

    public function updateStatusByBatch(int $batch, int $status): array
    {   
        return
        (array)$this->model->where(self::batch, $batch)
        ->update([
            self::status  => $status,
        ]);
    }
    

    public function updateDefaultSelections(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::default_selections  => $data['default_selections'],
            self::default_selections_veg => $data['default_selections_veg']
        ]);
    }

    public function delete(int $id): array
    {
        return [$this->model->where(self::primary_key, $id)->delete()];
    }

    public function search(): array
    {
        return [];
    }

    public function verify(string $value): string
    {
        return $this->model->where(self::name,$value)->count() > 0;
    }

    public function storeRules(): array
    {
        $rules = self::rules['store'];

        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules['edit'];

        return $rules;
    }

    public function deactivatePreviousCycle(int $currentCycleId)
    {
        $this->model
            ->where(self::primary_key,'<>',$currentCycleId)
            ->where(self::status,1)
            ->update([self::status => -1]);
    }

    public function deactivatePreviousCycleByBatch(int $batch): bool
    {
        $this->model
            ->where(self::batch,'<>',$batch)
            ->where(self::status,1)
            ->update([self::status => -1]);

        return true;
    }
    
    public function getAll()
    {
        return $this->model->get();
    }

    public function getAllByStatus($status, $orderBy = '')
    {   
        if ($status == 'all') {
            $data = $this->model->where(self::status, '<>',-1);
        } else {
            $data = $this->model->where(self::status,$status);   
        }
         
        if (!empty($orderBy)) {
            $data->orderBy('id',$orderBy);
        }
        
        return $data->get();
    }

    public function getAllByStatusWithActiveTiming($status, $orderBy = '')
    {   
        if ($status == 'all') {
            $data = $this->model->where('cycles.status', '<>',-1);
        } else {
            $data = $this->model->where('cycles.status',$status);   
        }
         
        if (!empty($orderBy)) {
            $data->orderBy('id',$orderBy);
        }   
        $data->select([
            'cycles.*'
        ]);
        $data->join('delivery_timings','delivery_timings.id','=','cycles.delivery_timings_id')
        ->where('delivery_timings.disabled',0);
        
        return $data->get();
    }

    public function getAllByStatusAndBatch($status, $batch)
    {   
        return $this->model
                    ->where([
                        self::status => $status, 
                        self::batch => $batch
                    ])->get();
    }
    
    public function iHaveTheTiming(int $id, int $status = 1)
    {
        return $this->model->where([self::delivery_timings_id => $id, self::status => $status])->limit(1)->count() > 0;
    }

    public function getActiveByTimingId(int $id)
    {
        return $this->model->where([self::delivery_timings_id => $id, self::status => 1])->first();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function getByBatch(int $batch)
    {
        return $this->model->where(self::batch,$batch)->get();
    }

    public function getActiveId()
    {
        $id = $this->model->where(self::status,1)->first();
        return isset($id->id) ? $id->id : 0;
    }

    public function getNextCycle(int $id)
    {
        return $this->model
                        ->where(self::primary_key,'>',$id)
                        ->orderBy(self::primary_key,'asc')
                        ->first();
    }

    public function getNextBatch(int $batch)
    {
        return $this->model
                        ->where(self::batch,'>',$batch)
                        ->orderBy(self::primary_key,'asc')
                        ->first();
    }

    public function getNextBatchByTiming(int $batch, int $timingId)
    {
        return $this->model
                        ->where(self::batch, '>', $batch)
                        ->where(self::delivery_timings_id, $timingId)
                        ->orderBy(self::primary_key, 'asc')
                        ->first();
    }

    public function getActiveByBatchAndTiming(int $batch, int $timingId)
    {
        return $this->model
                        ->where(self::status, 1)
                        ->where(self::batch, $batch)
                        ->where(self::delivery_timings_id, $timingId)
                        ->orderBy(self::primary_key, 'asc')
                        ->first();
    }

    public function getNewBatch()
    {
        $d = $this->model->select('batch')
                        ->orderBy(self::batch,'desc')
                        ->first();
        return isset($d->batch) ? (int)$d->batch+1 : 1;
    }

    public function getDinner(int $id)
    {
        return $this->model->find($id);    
    }

    public function getDefaultSelections(int $id): string
    {
        $data = $this->model->where(self::primary_key,$id)->first();
        return isset($data->default_selections) ? $data->default_selections : '';
    }  

    public function getVegDefaultSelections(int $id): string
    {
        $data = $this->model->where(self::primary_key,$id)->first();
        return isset($data->default_selections_veg) ? $data->default_selections_veg : '';
    }  
   
    public function setId($id) {
        $this->id = $id;
    }

    public function searchField(string $name): array
    {
        $data = [];
        foreach($this->repository->searchField($name) as $row) {
            $data[] = [
                'id'    => $row->id,
                'text'  => $row->meta_key
            ];
        }
        return [
            'items' => $data,
        ];
    }

    public function getActive()
    {
        return $this->model->where(self::status,1)->get();
    }

    public function setActiveByDate($deliveryDate, $cutoverDate)
    {
        return $this->model->where([
            self::delivery_date => $deliveryDate,
            self::cutover_date => $cutoverDate
        ])->update(['status' => 1]);
    }

    public function setActiveById(int $id)
    {
        return $this->model->where([
            self::primary_key => $id,
        ])->update(['status' => 1]);
    }

    public function iHaveTheCycle(int $id)
    {
        return $this->model->where(self::primary_key,$id)->limit(1)->count() > 0;
    }

    public function isEmpty()
    {
        return $this->model->limit(1)->count() <= 0;
    }

    public function getLatestRecordByTimingId(int $timingId)
    {
        return $this->model->where(self::delivery_timings_id, $timingId)->orderBy('id','desc')->limit(1)->get();
    }

    public function getByTimingAndBatch($timingId, $batch)
    {
        return $this->model->where([self::delivery_timings_id => $timingId, self::batch => $batch])->first();
    }

    public function getBatch()
    {
        return $this->model->select('batch')->orderBy('batch')->groupBy('batch')->get();
    }

    public function getDeliveryDate(int $cycleId)
    {
        $d = $this->model->select('delivery_date')->where(self::primary_key, $cycleId)->first();
        return $d->delivery_date ?? '';
    }

    public function getPrevious(int $deliveryTimingsId)
    {
        return $this->model
        ->where(self::status, '-1')
        ->where(self::delivery_timings_id, $deliveryTimingsId)
        ->orderBy('id','desc');
    }

    public function getFirstActiveCycle() 
    {
        return $this->model
        ->join('delivery_timings', 
            'delivery_timings.id', '=', 'cycles.delivery_timings_id'
        )
        ->where(self::status, '1')
        ->where('delivery_timings.disabled', '0')
        ->first();
    }

    public function hasDefaultCycleSelections(int $cycleId) {
        $selections = $this->model->find($cycleId);

        return (!empty($selections) && (!empty(json_decode($selections->default_selections)) || !empty(json_decode($selections->default_selections_veg)))) ? true : false;
    }
}
