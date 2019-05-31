<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\CyclesMealPlans;

use App\Rules\Custom;

use DB;

Class CycleMealPlansRepository
{	
    public $successSavedMessage = 'Successfully created new Cycle Meal Plan.';

    public $successUpdatedMessage = 'Successfully updated Cycle Meal Plan.';

    public $successDeletedMessage = "Successfully deleted Cycle Meal Plan.";

    public $errorDeleteMessage = "Sorry could not delete Cycle Meal Plan.";

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

    const meal_plans_id = 'meal_plans_id';

    const cycle_id = 'cycle_id';

    const default_selections = 'default_selections';

    const default_selections_veg = 'default_selections_veg';
    
    public $id;

    public function __construct() 
    {
        $this->model = new CyclesMealPlans;
    }

    public function store(array $data): array
    {   
        return
        (array)$this->model->create([
            self::cycle_id  => $data['cycle_id'],
            self::default_selections_veg => $data['default_selections_veg'],
            self::default_selections => $data['default_selections']
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::cycle_id  => $data['cycle_id'],
            self::default_selections_veg => $data['default_selections_veg'],
            self::default_selections => $data['default_selections']
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
        return $this->model->get();
    }

    public function getDinner(int $id)
    {
        return $this->model->find($id);    
    }

    public function getDefaultSelections(int $id): string
    {
        $data = $this->model->where(self::cycle_id,$id)->first();
        return isset($data->default_selections) ? $data->default_selections : '';
    }  

    public function getVegDefaultSelections(int $id): string
    {
        $data = $this->model->where(self::cycle_id,$id)->first();
        return isset($data->default_selections_veg) ? $data->default_selections_veg : '';
    }  
   
    public function setId($id) {
        $this->id = $id;
    }
}
