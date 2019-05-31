<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\DeliveryZoneTimings;
use App\Models\DeliveryZone;
use App\Models\DeliveryTimings;

use App\Rules\Custom;

use DB;

Class ZTRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Delivery Zone schedule.';

    public $successUpdatedMessage = 'Successfully updated Delivery Zone schedule.';

    public $successDeletedMessage = "Successfully deleted Delivery Zone schedule";

    public $errorDeleteMessage = "Sorry could not delete Delivery Zone schedule";

    const rules = [
        'store' => [
            'delivery_zone_id'  => 'required',
            'delivery_timings_id'  => 'required'
        ],

        'edit' => [
            'delivery_zone_id'  => 'required',
            'delivery_timings_id'  => 'required'
        ],
    ];

    const primary_key = 'id';
    
    const delivery_zone_id = 'delivery_zone_id';

    const delivery_timings_id = 'delivery_timings_id';

    public $id;

    public function __construct() 
    {
        $this->model = new DeliveryZoneTimings;
        $this->modelDZ = new DeliveryZone;
        $this->modelTiming = new DeliveryTimings;
    }

    public function store(array $data): array
    {   
        return
        (array)$this->model->create([
            self::delivery_zone_id      => $data['delivery_zone_id'],
            self::delivery_timings_id      => $data['delivery_timings_id']
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::delivery_zone_id      => $data['delivery_zone_id'],
            self::delivery_timings_id      => $data['delivery_timings_id']
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

        $rules['zone_timing'] = ['required', new Custom( 
            function($attribute, $value) 
            {
                list($delivery_zone_id, $delivery_timings_id) = $value;

                if($this->model->where([
                        self::delivery_zone_id => $delivery_zone_id,
                        self::delivery_timings_id => $delivery_timings_id
                    ])
                    ->count() > 0) {
                    return false;
                }

                return true;
            }
        )];

        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules['edit'];

        $rules['zone_timing'] = ['required', new Custom( 
            function($attribute, $value) 
            {
                list($delivery_zone_id, $delivery_timings_id) = $value;

                if($this->model->where([
                        self::delivery_zone_id => $delivery_zone_id,
                        self::delivery_timings_id => $delivery_timings_id
                    ])
                    ->where(self::primary_key,'<>',$this->id)
                    ->count() > 0) {
                    return false;
                }

                return true;
            }
        )];

        return $rules;
    }

    public function getAll()
    {
        return $this->model
        ->select([
            'delivery_zone_timings.id as id',
            'zone_name',
            'delivery_day',
            'cutoff_day',
            'cutoff_time',
            'delivery_zone_id',
            'delivery_timings_id'
        ])
        ->join('delivery_timings','delivery_timings.id','=','delivery_zone_timings.delivery_timings_id')
        ->join('delivery_zones','delivery_zones.id','=','delivery_zone_timings.delivery_zone_id')
        ->where('delivery_zones.disabled',0)
        ->where('delivery_timings.disabled',0)
        ->orderBy('zone_name','asc')
            ->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);    
    }

    public function getZones() {
        return $this->modelDZ->get();
    }

    public function getTimings($zoneId = null) {
        return $this->modelTiming->where('disabled',0)->get();
    }

    public function getActiveLocations()
    {
        return $this->model
        ->join('delivery_timings','delivery_timings.id','=','delivery_zone_timings.delivery_timings_id')
        ->join('delivery_zones','delivery_zones.id','=','delivery_zone_timings.delivery_zone_id')
        ->where('delivery_timings.disabled',0)
        ->where('delivery_zones.disabled',0)
        ->get();
    }

    public function getTimingsByZoneId(int $zoneId)
    {
        return $this->model->where(self::zone)->get();
    }

    public function getTimingsIdById(int $id)
    {
        $d = $this->model->where(self::primary_key,$id)->first();
        return isset($d->delivery_timings_id) ? $d->delivery_timings_id : 0;
    }

    public function getDeliveryZoneIdById(int $id)
    {
        $d = $this->model->where(self::primary_key,$id)->first();
        return isset($d->delivery_zone_id) ? $d->delivery_zone_id : 0;
    }

    public function getDeliveryZoneLocation(int $id)
    {
        $id = $this->get($id);
        $id = $id->delivery_zone_id ?? 0;
        if (empty($id)) {
            return '';
        }
        return $this->modelDZ->find($id)->zone_name ?? '';
    }

    public function getDeliveryZoneAddress(int $id)
    {
        $id = $this->get($id);
        $id = $id->delivery_zone_id ?? 0;
        if (empty($id)) {
            return '';
        }
        return $this->modelDZ->find($id)->delivery_address ?? '';
    }

    public function setId($id) {
        $this->id = $id;
    }
}
