<?php

namespace App\Repository;

use App\Services\CRUDInterface;

use App\Models\MealsStatusChange;
use App\Rules\Custom;

use Session;

Class MealsChangeStatusRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Meal.';

    public $successUpdatedMessage = 'Successfully updated Meal.';

    public $successDeletedMessage = "Successfully deleted Meal.";

    public $errorDeleteMessage = "Sorry could not delete Meal.";

	const rules = [
		'store' => [
    			'meal_sku' 	 => 'required|max:45',
    			'meal_name' 	 => 'required'
    		],

        'edit' => [
                'meal_sku'   => 'required|max:45',
                'meal_name'      => 'required'
            ]
	];

    const primary_key = 'id';

    const cycle_id = 'cycle_id';

    const meal_ids_remove = 'meal_ids_remove';

    const meal_ids_add = 'meal_ids_add';

    public $id;

	public function __construct() 
    {
        $this->model = new MealsStatusChange;
    }

    public function store(array $data): array
    {   
        $data = $this->model->create([
            self::cycle_id => $data['cycle_id'],
            self::meal_ids_remove => json_encode($data['meal_ids_remove']),
            self::meal_ids_add => json_encode($data['meal_ids_add'])
        ]);
        
        $this->setId($data->id);

        return (array)$data;
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where(self::cycle_id, $data['cycle_id'])
        ->update([
            self::meal_ids_remove => json_encode($data['meal_ids_remove']),
            self::meal_ids_add => json_encode($data['meal_ids_add'])
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
        if($this->model->where(self::sku,$value)->count() <= 0) {
            return $this->modelPlan->where(self::plan_sku,$value)->count();
        }   
        return true;
    }

    public function storeRules(): array
    {
        $rules = self::rules['store'];

        $rules['meal'] = ['required', new Custom( function($attribute, $value) {
            list($meal_sku, $meal_name) = $value;

            if($this->model
                    ->where([
                        self::sku  => $meal_sku,
                        self::meal => $meal_name
                    ])
                    ->count() > 0
                ) {
                return false;
            }

            return $this->verify($meal_sku) == true ? false : true;
        })];

        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules['edit'];

        $rules['meal'] = ['required', new Custom( function($attribute, $value) {
            list($meal_sku, $meal_name) = $value;

            if($this->model
                    ->where([
                        self::sku  => $meal_sku,
                        self::meal => $meal_name
                    ])
                    ->where(self::primary_key, '<>', $this->id)
                    ->count() > 0
                ) {
                return false;
            }

            if($this->model
                    ->where([
                        self::sku  => $meal_sku
                    ])
                    ->where(self::primary_key, '<>', $this->id)
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
        return $this->model->where(self::cycle_id, $id)->get();
    }

    private function setId(int $id)
    {
        $this->id = $id;
    }

    public function getByCycleId(int $id)
    {
        return $this->model->where(self::cycle_id, $id)->get();
    }


    public function mealsAdd(int $id)
    {   
        $meals = $this->model->where(self::cycle_id, $id)->first();
        return isset($meals->meal_ids_add) ? json_decode($meals->meal_ids_add) : [];
    }

    public function mealsRemove(int $id)
    {
        $meals = $this->model->where(self::cycle_id, $id)->first();
        return isset($meals->meal_ids_remove) ? json_decode($meals->meal_ids_remove) : [];
    }
}
