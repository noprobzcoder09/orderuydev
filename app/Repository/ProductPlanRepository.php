<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\MealPlans;
use App\Rules\Custom;

use App\Services\InfusionSoftServices;

Class ProductPlanRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Meals Plan.';

    public $successUpdatedMessage = 'Successfully updated Meals Plan.';

    public $successDeletedMessage = "Successfully deleted Meals Plan.";

    public $errorDeleteMessage = "Sorry could not delete Meals Plan.";

    const rules = [
        'store' => [
            'plan_name'         => 'required',
            'sku'               => 'required|unique:meal_plans',
            'ins_product_id'    => 'required',
            'no_meals'          => 'required',
            'vegetarian'        => 'required',
            'price'             => 'required',
            'meal_plan_image'   => 'required|image|max:2024'
        ],

        'edit' => [
            'sku'               => 'required',
            'plan_name'         => 'required',
            'ins_product_id'    => 'required',
            'no_meals'          => 'required',
            'vegetarian'        => 'required',
            'price'             => 'required',
            // 'meal_plan_image'   => 'required|image'
        ],
    ];

    const primary_key = 'id';

    const sku = 'sku';

    const plan_name = 'plan_name';

    const ins_product_id = 'ins_product_id';

    const no_meals = 'no_meals';

    const vegetarian = 'vegetarian';

    const price = 'price';

    const meal_plan_image = 'meal_plan_image';

    const no_days = 'no_days';

    public $id;


    public function __construct(int $planId = null) 
    {
        $this->model = new MealPlans;
        if (!empty($planId)) {
            $this->id = $planId;
            $this->setRow($planId);
        }
    }

    public function store(array $data): array
    {   
        $data = $this->model->create([
            self::sku                   => $data['sku'],
            self::plan_name             => $data['plan_name'],
            self::ins_product_id        => $data['ins_product_id'],
            self::no_meals              => $data['no_meals'],
            self::no_days               => $data['no_days'],
            self::vegetarian            => $data['vegetarian'],
            self::price                 => $data['price'],
            self::meal_plan_image       => ''
        ]);

        $this->setId($data->id);

        return (array)$data;
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::sku                   => $data['sku'],
            self::plan_name             => $data['plan_name'],
            self::ins_product_id        => $data['ins_product_id'],
            self::no_meals              => $data['no_meals'],
            self::no_days               => $data['no_days'],
            self::vegetarian            => $data['vegetarian'],
            self::price                 => $data['price'],
        ]);
    }

    public function storeFile(int $id, string $file): string
    {   
        $this->model
            ->where(self::primary_key, $id)
            ->update([
                self::meal_plan_image => $file
            ]);
        return $file;
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
        return $this->model->where(self::sku,$value)->count() > 0;
    }

    public function storeRules(): array
    {
        $rules = self::rules['store'];

        $rules['sku'] = ['required', new Custom( function($attribute, $value) {
            if($this->model
                    ->where($attribute, $value)
                        ->count() > 0
                ) {
                return false;
            }
            return true;
        })];

        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules['edit'];

        $rules['sku'] = ['required', new Custom( function($attribute, $value) {
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

    public function whereIn(array $id)
    {
        return $this->model->whereIn(self::primary_key, $id)->get();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getNoMeals(): int
    {
        return $this->model->find($this->id)->no_meals | 0;
    }

    public function getNoDays(): int
    {
        return $this->model->find($this->id)->no_days | 0;
    }
    
    public function getPrice(): float
    {
        return $this->model->find($this->id)->price | 0;
    }

    public function getINFSProductId(): int
    {
        return $this->model->find($this->id)->ins_product_id | 0;
    }
    

    public function getPlanImage(): string
    {
        return $this->model->find($this->id)->meal_plan_image | '';
    }

    public function getPlanName(): string
    {
        return $this->model->find($this->id)->plan_name | '';
    }

    public function isVegetarian(): bool
    {   
        return $this->model->find($this->id)->vegetarian;
    }

    public function getINFSProduct()
    {   
        $table_name = "Product";
        $query = array("Id"=>"%%");
        $this->infs = new InfusionSoftServices;
        return $this->infs->queryTable($table_name, $query);
    }

    public function setRow(int $planId) 
    {
        $this->row = $this->get($planId);
    }

    public function whereNotIn(array $ids)
    {
        return $this->model->whereNotIn(self::primary_key, $ids)->get();
    }

    public function getIdBySku(string $sku)
    {
        $d = $this->model->where('sku',$sku)->first();
        return $d->id ?? 0;
    }

    public function getAvailableMealPlans()
    {
        return $this->model->where('deleted_at', NULL)->get();
    }
}
