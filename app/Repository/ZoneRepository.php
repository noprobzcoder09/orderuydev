<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\DeliveryZone;
use App\Rules\Custom;

Class ZoneRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Delivery Zone.';

    public $successUpdatedMessage = 'Successfully updated Delivery Zone.';

    public $successDeletedMessage = "Successfully deleted Delivery Zone";

    public $errorDeleteMessage = "Sorry could not delete Delivery Zone";

    const rules = [
        'store' => [
            'zone_name'  => 'required'
        ],

        'edit' => [
            'zone_name'   => 'required'
        ],
    ];

    const primary_key = 'id';

    const name = 'zone_name';

    const delivery_address = 'delivery_address';

    public $id;

    public function __construct() 
    {
        $this->model = new DeliveryZone;
    }

    public function store(array $data): array
    {   
        return
        (array)$this->model->create([
            self::name              => $data['zone_name'],
            self::delivery_address  => $data['delivery_address']
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::name              => $data['zone_name'],
            self::delivery_address  => $data['delivery_address']
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

        $rules['zone_name'] = ['required', new Custom( function($attribute, $value) {
            if($this->model->where($attribute, $value)->count() > 0) {
                return false;
            }
            return true;
        })];

        
        return $rules;
    }

    public function updateRules(): array
    {   
        $rules = self::rules['edit'];

        $rules['zone_name'] = ['required', new Custom( function($attribute, $value) {
            if($this->model
                    ->where($attribute, $value)
                    ->where(self::primary_key,'<>',$this->id)
                        ->count() > 0
                ) {
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

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function empty(int $id)
    {
        $out = $this->get($id);
        return isset($out->id) ? false : true;
    }

    public function disabled(int $id, bool $disabled)
    {           
        return $this->model->where(self::primary_key, $id)
        ->update([
            'disabled' => $disabled
        ]);
    }

    public function hasCustomerAttached(int $deliveryZoneId)
    {
        return $this->model
        ->join('delivery_zone_timings',
            'delivery_zone_timings.delivery_zone_id','=','delivery_zones.id'
        )
        ->join('user_details',
            'user_details.delivery_zone_timings_id','=','delivery_zone_timings.id'
        )
        ->where('delivery_zones.id',$deliveryZoneId)
        ->whereIn('user_details.status',['active','billing issue','paused'])
        ->limit(1)
            ->count() > 0;
    }

    public function getCustomersCancelledAttached(int $deliveryZoneId)
    {
        return $this->model
        ->select('user_details.user_id')
        ->join('delivery_zone_timings',
            'delivery_zone_timings.delivery_zone_id','=','delivery_zones.id'
        )
        ->join('user_details',
            'user_details.delivery_zone_timings_id','=','delivery_zone_timings.id'
        )
        ->where('delivery_zones.id',$deliveryZoneId)
        ->whereIn('user_details.status',['cancelled'])
            ->get();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
}
