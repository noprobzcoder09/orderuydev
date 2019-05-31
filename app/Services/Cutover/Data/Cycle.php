<?php

namespace App\Services\Cutover\Data;

use App\Models\Cycles as Model;
use DB;

Class Cycle
{   
    private static $days = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    ];

    private static $noCycle = 20;

    public function __construct()
    {
        $this->cycle = new Model;
    }

    public function active()
    {
        $active = [];
        foreach($this->base()->get() as $row) {
            $active[] = [
                'id' => $row->id,
                'delivery_timings_id' => $row->delivery_timings_id
            ];
        }
        return $active;
    }

    public function base()
    {
        return $this->cycle->where('status',1);
    }

    public function store(array $data)
    {
        $model = new Model;
        $model->delivery_timings_id = $data['delivery_timings_id'];
        $model->delivery_date = $data['delivery_date'];
        $model->cutover_date = $data['cutover_date'];
        $model->default_selections = $data['default_selections'];
        $model->default_selections_veg = $data['default_selections_veg'];
        $model->status = $data['status'];
        $model->batch = $data['batch'];

        $model->save();
    }

    public function getNewBatchByTimingId(int $deliveryTimingId)
    {
        $d = $this->cycle->select('batch')
        ->orderBy('batch','desc')
        ->where('delivery_timings_id',$deliveryTimingId)
        ->first();
        return isset($d->batch) ? (int)$d->batch+1 : 1;
    }
    
    public function getLastRecord(int $timingId)
    {
        return $this->cycle->where('delivery_timings_id', $timingId)
        ->orderBy('id','desc')->first();
    }

    public function getById(int $id)
    {
        return $this->cycle->where('id', $id)
        ->get();
    }

    public function getActiveTimingId(int $id)
    {
        return $this->cycle->where(
            ['delivery_timings_id' => $id, 'status' => 1
        ])->get();
    }

    public function getCurrentActiveByTimingId(int $timingId)
    {
        return $this->cycle
        ->where('delivery_timings_id', $timingId)
        ->where('status','1')
            ->first();
    }

    public function activate(int $batch)
    {
        return $this->cycle->where('batch', $batch)
        ->update(['status' => 1]);
    }


    public function deactivate(int $deliveryTimingId, $cutoverDate)
    {   
        $t = $this->cycle
        ->where('cutover_date','<', $cutoverDate)
        ->where('delivery_timings_id', $deliveryTimingId)
        ->count();
        echo $t.'<br>';
        print_r(func_get_args());

        return $this->cycle
        ->where('cutover_date','<', $cutoverDate)
        ->where('delivery_timings_id', $deliveryTimingId)
        ->update(['status' => -1]);
    }

    public function getNextBatch(int $batch)
    {
        $d = $this->cycle->where('batch','>',$batch)
        ->orderBy('id','asc')
        ->first();

        return $d->batch ?? 0;
    }

    public function update(array $data, array $where)
    {   
        return $this->cycle
        ->where($where)
        ->update([
            'default_selections'  => $data['default_selections'],
            'default_selections_veg' => $data['default_selections_veg'],
            'status' => $data['status']
        ]);
    }

    public function getByBatch(int $batch)
    {
       return $this->cycle->where('batch',$batch)->get();
    }

    public function getByTimingAndBatch(int $deliveryTimingId, int $batch)
    {
       return $this->cycle->where([
            'batch' => $batch,
            'delivery_timings_id' => $deliveryTimingId
        ])->get();
    }

    public function getByTimingAndCutoffDate(int $deliveryTimingId, $date)
    {
       return $this->cycle->where([
            'cutover_date' => $date,
            'delivery_timings_id' => $deliveryTimingId
        ])->get();
    }

    public function getByCurrentDate($date = '')
    {   
       $cycle = array();
        foreach(DB::table('delivery_timings')->where('disabled',0)->get() as $row) {
            if (empty($date))
                $model = $this->cycle
               ->whereRaw('cutover_date >= CURDATE()')
               ->where('delivery_timings_id',$row->id)
               ->orderBy('cutover_date','asc');

            $model = $this->cycle
           ->whereRaw("cutover_date >= '".$date."'")
           ->where('delivery_timings_id',$row->id)
           ->orderBy('cutover_date','asc');

           $model = $model->first();

           if (empty($model)) continue;

            array_push($cycle, array(
                'id' => $model->id,
                'delivery_date' => $model->delivery_date,
                'delivery_timings_id' => $model->delivery_timings_id,
                'cutover_date' => $model->cutover_date,
                'status' => $model->status
            ));
        }

        return $cycle;
    }

    public function getByCurrentDateAtCutOffTime($date = '')
    {   
        $cycle = array();
        foreach(DB::table('delivery_timings')->where('disabled',0)->get() as $row) {
            $model = $this->cycle
            ->select([
                'cycles.status',
                'cycles.id', 
                'delivery_date', 
                'delivery_timings_id', 
                'cutover_date'
            ])
            ->join('delivery_timings','delivery_timings.id','cycles.delivery_timings_id')
            ->where('delivery_timings_id', $row->id)
            ->orderBy('cutover_date','asc');

            if (empty($date)) {
                $model->whereRaw("CONCAT(cutover_date,' ',cutoff_time) > concat(CURDATE(),' ',CURRENT_TIME())");
            } else {
                $model->whereRaw("CONCAT(cutover_date,' ',cutoff_time) > '".$date."'");
            }
            $model = $model->first();

            if (empty($model)) continue;

            array_push($cycle, array(
                'id' => $model->id,
                'delivery_date' => $model->delivery_date,
                'delivery_timings_id' => $model->delivery_timings_id,
                'cutover_date' => $model->cutover_date,
                'status' => $model->status
            ));
        }
        
        return $cycle;
    }

    public function getPreviousByTimingAndCutoffDate(int $deliveryTimingId, \DateTime $date)
    {
       return $this->cycle->where([
            'delivery_timings_id' => $deliveryTimingId
        ])
        ->where('cutover_date','<', $date->format('Y-m-d'))
        ->orderBy('id','desc')
        ->limit(1)
        ->first();
    }

    public function getNextByTimingAndCutoffDate(int $deliveryTimingId, \DateTime $date)
    {
       return $this->cycle->where([
            'delivery_timings_id' => $deliveryTimingId
        ])
        ->where('cutover_date','>', $date->format('Y-m-d'))
        ->orderBy('id','asc')
        ->limit(1)
        ->first();
    }
    

    public function isEmpty()
    {
        return $this->cycle->limit(1)->count() <= 0;
    }

    public function isNeedtoGenerate()
    {
        $pending = $this->cycle->where('status',0)->groupBy('delivery_timings_id')->get();
        return count($pending) <= 1 ? true : false;
    }

    public function getDays()
    {
        return static::$days;
    }

    public function getNoCycle()
    {
        return static::$noCycle;
    }

    public function getDefaultMenu(int $currentCycleId, bool $isVege)
    {
        $cycle = $this->cycle->find($currentCycleId);
        if ($isVege) {
            return $cycle->default_selections_veg;
        }
        return $cycle->default_selections;
    }


    public function updateDefaultMeals(int $cycleId, array $nonVego, array $vego) 
    {   
        return $this->cycle
        ->where('id', $cycleId)
        ->update([
            'default_selections'  => json_encode($nonVego),
            'default_selections_veg' => json_encode($vego)
        ]);
    }

}
