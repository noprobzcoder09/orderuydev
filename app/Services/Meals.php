<?php

namespace App\Services;

use App\Services\Meals\Validator;
use App\Services\Meals\Messages;
use App\Services\Meals\Redirect;

use Request;

Class Meals
{	

    use Redirect;

	public function __construct(\App\Repository\MealsRepositoryInterface  $mealsRepository)
	{
		$this->mealsRepository = new $mealsRepository;
        $this->message = new Messages;
	}

	public function mealStore(): array
    {	
    	$validator = new Validator;
    	$meal = $validator->mealValidate($this->mealsRepository->mealRules());

    	$response['status'] = 200;

    	$response['success'] = $validator->isValid;

        $response['redirectUrl'] =  url($this->mealsEditUrl.'1');

        if ($validator->isValid)  
        {
            $response['messages'] = $this->message->successNewMeal;

            $this->mealsRepository->storeMeal(Request::all());
        }

    	else $response['messages'] = $validator->filterError($validator->messages);

        return $response;
    }

    public function metaStore($view): array
    {   
        $validator = new Validator;
        $meal = $validator->mealValidate($this->mealsRepository->metaRules());

        $response['status'] = 200;
        $response['success'] = $validator->isValid;

        if ($validator->isValid) 
        {
            $response['messages'] = $this->message->successNewMeta;

            $this->mealsRepository->storeMeta(Request::all());

            $response['metas'] = view($view.'table-meta',['metas' => $this->mealsRepository->getMetas()])->render();
        } 

        else $response['messages'] = $validator->filterError($validator->messages);

        return $response;
    }

    public function metaDelete(): array
    {   
        
        $response['status'] = 200;
        $response['success'] = false;


        if ($response['success'] = $this->mealsRepository->removeMeta(Request::get('id'))) {
            $response['messages'] = $this->message->successRemoveMeta;
        } 

        else $response['messages'] = $this->message->errorRemoveMeta;

        return $response;
    }

    

    public function meals()
    {
        return [
            'meal'  => $this->mealsRepository->getMeal(),
            'sku'  => $this->mealsRepository->getSku(),
            'vegetarian'  => $this->mealsRepository->getVegetarian(),
            'metas' => $this->mealsRepository->getMetas()
        ];
    }
    
}
