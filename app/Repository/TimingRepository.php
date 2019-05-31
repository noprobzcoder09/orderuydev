<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\DeliveryTimings;
use App\Rules\Custom;
use DB;

Class TimingRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Delivery Schedule.';

    public $successUpdatedMessage = 'Successfully updated Delivery Schedule.';

    public $successDeletedMessage = "Successfully deleted Delivery Schedule";

    public $errorDeleteMessage = "Sorry could not delete Delivery Schedule";

    const rules = [
        'store' => [
            'delivery_day'  => 'required',
            'cutoff_day'  => 'required',
            'cutoff_time'  => 'required'
        ],

        'edit' => [
            'delivery_day'  => 'required',
            'cutoff_day'  => 'required',
            'cutoff_time'  => 'required'
        ],
    ];

    const primary_key = 'id';

    const delivery_day = 'delivery_day';

    const cutoff_day = 'cutoff_day';

    const cutoff_time = 'cutoff_time';

    public $id;


    public function __construct() 
    {
        $this->model = new DeliveryTimings;
    }

    public function store(array $data): array
    {   
        return
        (array)$this->model->create([
            self::delivery_day    => $data['delivery_day'],
            self::cutoff_day      => $data['cutoff_day'],
            self::cutoff_time     => $data['cutoff_time'],
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where(self::primary_key, $data['id'])
        ->update([
            self::delivery_day    => $data['delivery_day'],
            self::cutoff_day      => $data['cutoff_day'],
            self::cutoff_time     => $data['cutoff_time']
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

        $rules['timing'] = ['required', new Custom( function($attribute, $value) {

            list($delivery_day, $cutoff_day, $cutoff_time) = $value;

            if($this->model->where([
                    self::delivery_day => $delivery_day,
                    self::cutoff_day => $cutoff_day,
                    self::cutoff_time => $cutoff_time
                ])
                ->count() > 0) {
                return false;
            }
            return true;
        })];

        
        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules['edit'];

        $rules['timing'] = ['required', new Custom( function($attribute, $value) {

            list($delivery_day, $cutoff_day, $cutoff_time) = $value;
            
            if($this->model->where([
                    self::delivery_day => $delivery_day,
                    self::cutoff_day => $cutoff_day,
                    self::cutoff_time => $cutoff_time
                ])
                ->where(self::primary_key,'<>',$this->id)
                ->count() > 0) {
                return false;
            }
            return true;
        })];

        
        return $rules;
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getAllActive()
    {
        return $this->model->where('disabled',0)->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function disabled(int $id, bool $disabled)
    {           
        return $this->model->where(self::primary_key, $id)
        ->update([
            'disabled' => $disabled
        ]);
    }

    public function hasCustomerAttached(int $deliveryTimingsId)
    {
        return $this->model
        ->join('delivery_zone_timings',
            'delivery_zone_timings.delivery_timings_id','=','delivery_timings.id'
        )
        ->join('user_details',
            'user_details.delivery_zone_timings_id','=','delivery_zone_timings.id'
        )
        ->where('delivery_timings.id',$deliveryTimingsId)
        ->whereIn('user_details.status',['active','billing issue','paused'])
        ->limit(1)
            ->count() > 0;
    }

    public function clearFutureCyclesByTimingId(int $deliveryTimingsId) 
    {
        DB::table('cycles')->where('delivery_timings_id', $deliveryTimingsId)
            ->where('status', 0)
            ->delete();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
}
