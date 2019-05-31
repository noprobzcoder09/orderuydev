<?php

namespace App\Repository;

use App\Services\CRUDInterface;

use App\Models\Meals;
use App\Models\MealPlans;
use App\Rules\Custom;

use Session;
use DB;

Class MealsRepository implements CRUDInterface
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

    const sku = 'meal_sku';

    const plan_sku = 'sku';

    const meal = 'meal_name';

    const vegetarian = 'vegetarian';

    const status = 'status';

    const image = 'meal_image';

    const field = 'field';

    const value = 'value';

    public $id;

	public function __construct() 
    {
        $this->model = new Meals;
        $this->modelPlan = new MealPlans;
    }

    public function store(array $data): array
    {   
        $data = $this->model->create([
            self::sku                   => $data['meal_sku'],
            self::meal                  => $data['meal_name'],
            self::vegetarian            => $data['vegetarian'],
            self::status                => 1
        ]);
        
        $this->setId($data->id);

        return (array)$data;
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::sku                   => $data['meal_sku'],
            self::meal                  => $data['meal_name'],
            self::vegetarian            => $data['vegetarian'],
            self::status                => $data['status']
        ]);
    }

    public function updateStatus(int $id, int $status): array
    {   
        return
        (array)$this->model->where('id', $id)
        ->update([
            self::status => $status
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

    public function getAllWithTrash()
    {
        return $this->model->withTrashed()->get();
    }

    public function getAllByStatus($status)
    {   
        if ($status == 'all') {
            return $this->getAll();
        }
        return $this->model->where(self::status, $status)->get();
    }
    
    public function getVegetarian()
    {
        return $this->model->where(self::vegetarian, true)->get();
    }

    public function getNonvegetarian()
    {
        return $this->model->where(self::vegetarian, false)->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function getByArray(array $id)
    {
        return $this->model->whereIn('id',$id)->get();
    }

    public function isVegetarian(int $id)
    {
        return $this->model->where([self::primary_key => $id, 'vegetarian' => true])->count() > 0;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }   

    public function getActive()
    {
        return $this->model
                        ->select(['id','meal_name'])
                        ->where('status',1)
                        ->get();
    }

    // public function getActiveByCycle()
    // {   
    //     $meals = DB::table('meals_status_changes')
    //     ->whereRaw('cycle_id in (select id from cycles where status=1)')
    //     ->first();

    //     $meals_to_remove = json_decode($meals->meal_ids_remove ?? '');
    //     $meals_to_remove = empty($meals_to_remove) ? [] : $meals_to_remove;

    //     return $this->model
    //                     ->select(['id','meal_name'])
    //                     ->where('status',1)
    //                     ->whereNotIn('id',$meals_to_remove)
    //                     ->get();
    // }
    
    public function getActiveMealsWithTopNonVego()
    {
        $vego = $this->model
                        ->select(['id','meal_name'])
                        ->where('status',1)
                        ->where('vegetarian',1);
        
        $model = $this->model
        ->select(['id','meal_name'])
        ->where('status',1)
        ->where('vegetarian',0)
        ->union($vego);

        return $model->get();
    }

    public function getActiveNonVegetarian()
    {
        return $this->model
                        ->select(['id','meal_name'])
                        ->where('status',1)
                        ->where('vegetarian',0)
                        ->get();
    }

    public function getActiveVegetarian()
    {
        return $this->model
                        ->select(['id','meal_name'])
                        ->where('status',1)
                        ->where('vegetarian',1)
                        ->get();
    }

    public function getInactive()
    {
        return $this->model
                        ->select(['id','meal_name'])
                        ->where('status',0)
                        ->get();
    }

    // public function getActiveMealsByActiveCycle(bool $isVegetarian = false)
    // {   
    //      $meals = DB::table('meals_status_changes')
    //     ->whereRaw('cycle_id in (select id from cycles where status=1)')
    //     ->first();

    //     $meals_to_remove = json_decode($meals->meal_ids_remove ?? '');
    //     $meals_to_remove = empty($meals_to_remove) ? [] : $meals_to_remove;

    //     return $this->model
    //                     ->where('status',1)
    //                     ->where(self::vegetarian, $isVegetarian)
    //                     ->whereNotIn('id',$meals_to_remove)
    //                     ->get();

    // }

    public function getMeals(int $noMeals, bool $isVegetarian)
    {
        return $this->model
                    ->orderBy(self::primary_key, 'asc')
                    ->where(self::vegetarian, $isVegetarian)
                    ->where('status',1)
                        ->get();
    }

    public function getDinner(int $noMeals)
    {
        return $this->model
                    ->where(self::vegetarian, false)
                    ->limit($noMeals > 5 ? $noMeals/2 : $noMeals)
                    ->orderBy(self::primary_key, 'asc')
                    ->where('status',1)
                        ->get();
    }

    public function getVegeDinner(int $noMeals)
    {
        return $this->model
                    ->where(self::vegetarian, true)
                    ->limit($noMeals > 5 ? $noMeals/2 : $noMeals)
                    ->orderBy(self::primary_key, 'asc')
                    ->where('status',1)
                        ->get();
    }

    public function getLunch(int $noMeals)
    {
        return $this->model
                    ->where(self::vegetarian, false)
                    ->offset($noMeals > 5 ? $noMeals/2 : $noMeals)
                    ->limit($noMeals)
                    ->orderBy(self::primary_key, 'asc')
                    ->where('status',1)
                        ->get();
    }

    public function getVegeLunch(int $noMeals)
    {
        return $this->model
                    ->where(self::vegetarian, true)
                    ->offset($noMeals > 5 ? $noMeals/2 : $noMeals)
                    ->limit($noMeals)
                    ->orderBy(self::primary_key, 'asc')
                    ->where('status',1)
                        ->get();
    }

    public function getMealsByIds(array $mealIds)
    {
        return $this->model
                    ->whereIN(self::primary_key,$mealIds)
                        ->get();
    }

    public function activateMealsInArray(array $ids)
    {
        return $this->model->whereIn('id',$ids)
                    ->update([
                        'status' => 1
                    ]);
    }

    public function deactivateMealsNotInArray(array $ids)
    {
        return $this->model->whereNotIn('id',$ids)
                    ->update([
                        'status' => 0
                    ]);
    }


    public function deactivateMealsInArray(array $ids)
    {
        return $this->model->whereIn('id',$ids)
                    ->update([
                        'status' => 0
                    ]);
    }

}
