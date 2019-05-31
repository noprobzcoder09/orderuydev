<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\CyclesMealPlans;

use App\Rules\Custom;

use DB;

Class CyclePlansRepository
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

    const cycle_id = 'cycle_id';

    const status = 'status';

    const default_selections = 'default_selections';

    const default_selections_veg = 'default_selections_veg';
    
    public $id;

    public function __construct() 
    {
        $this->model = new CyclesMealPlans;
    }

    public function store(array $data): array
    {   
        
        $data = $this->model->create([
            self::default_selections  => $data['default_selections'],
            self::default_selections_veg => $data['default_selections_veg'],
            self::cycle_id => $data['cycle_id'],
        ]);

        $this->setId($data->id);

        return (array)$data;
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where(self::cycle_id, $data['cycle_id'])
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

    public function getAll()
    {
        return $this->model->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function getActiveId()
    {
        return $this->model->where(self::status,1)->first()->id;
    }

    public function iHaveTheCycle(int $id)
    {
        return $this->model->where(self::cycle_id,$id)->limit(1)->count() > 0;
    }

    public function getDefaultSelections(): string
    {
        $data = $this->model->where(self::primary_key,$this->id)->first();
        return isset($data->default_selections) ? $data->default_selections : '';
    }
   
    public function setId($id) {
        $this->id = $id;
    }
    
}
