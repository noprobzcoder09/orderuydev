<?php

namespace App\Repository;

use App\Services\CRUDInterface;

use App\Models\Meals;
use App\Models\MealsMeta;
use App\Rules\Custom;

use Session;

Class MetaRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Meta.';

    public $successUpdatedMessage = 'Successfully updated Meta.';

    public $successDeletedMessage = "Successfully deleted Meta.";

    public $errorDeleteMessage = "Sorry could not delete Meta.";

	const rules = [
		'store' => [
    			'meal_id' 	 => 'required',
    			'meta_key' 	 => 'required|max:45',
                'meta_value'   => 'required'
    		],

        'storeKey' => [
                'search_field'    => 'required'
            ],

        'edit' => [
                'meal_id'    => 'required',
                'meta_key'   => 'required|max:45',
                'meta_value'   => 'required'
            ]
	];

    const primary_key = 'id';

    const meal_id = 'meal_id';

    const meta_key = 'meta_key';

    const meta_value = 'meta_value';

	public function __construct() 
    {
        $this->model = new MealsMeta;
    }

    public function store(array $data): array
    {   
        return
        (array)$this->model->create([
            self::meal_id   => $data['meal_id'],
            self::meta_key  => $data['meta_key'],
            self::meta_value => $data['meta_value']            
        ]);
    }

    public function storeKey(array $data): array
    {   
        return
        (array)$this->model->create([
            self::meal_id   => $data['meal_id'],
            self::meta_key  => $data['meta_key'],
            self::meta_value => $data['meta_value']
        ]);
    }
    

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::meta_key  => $data['meta_key'],
            self::meta_value => $data['meta_value']  
        ]);
    }

    public function delete(int $id): array
    {
        return [$this->model->where(self::primary_key, $id)->delete()];
    }

    public function search(): array
    {
        return $this->model->where(self::meta_key, $name)->get();
    }

    public function searchField(string $name)
    {
        return $this->model->where(self::meta_key, 'like','%'.$name.'%')->limit(50)->get();
    }

    public function verify(string $value): string
    {
        return $this->model->where(self::meta_key,$value)->count();
    }

    public function storeRulesMetaKey(): array
    {
        $rules = self::rules['storeKey'];

        return $rules;
    }

    public function storeRules(): array
    {
        return self::rules['store'];
    }

    public function updateRules(): array
    {
        return self::rules['edit'];
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function get(int $id)
    {
        return $this->model->where(self::meal_id,$id)->get();
    }

    public function getByKey(int $id)
    {
        return $this->model->select(['id','meta_key','meta_value','meal_id'])->find($id);
    }
}
