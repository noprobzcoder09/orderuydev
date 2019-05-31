<?php

namespace App\Services;

use App\Services\Cutover\Cycle\Selections\Get;
use App\Services\Cutover\Data\Cycle as ModelCycle;
use App\Repository\CycleRepository;
use App\Repository\MealsRepository;
use App\Repository\MealsChangeStatusRepository;

class Cycle
{   
    public function __construct()
    {
        $this->cycleRepo = new CycleRepository;
        $this->mealsRepo = new MealsRepository;
        $this->mealsStatusRepo = new MealsChangeStatusRepository;
    }

    public function getActive()
    {
        return $this->cycleRepo->getActive();
    }

    public function getAll()
    {
        return $this->cycleRepo->getAll();
    }

    public function getAllByStatus($status)
    {   
        return $this->cycleRepo->getAllByStatus($status);
    }

    public function getAllByStatusWithActiveTiming($status)
    {   
        return $this->cycleRepo->getAllByStatusWithActiveTiming($status);
    }
    
    public function get(int $id)
    {
        return $this->cycleRepo->get($id);
    }

    public function activeMeals()
    {
        return $this->mealsRepo->getAll();
    }

    public function currentMealsAdd(int $id)
    {
        return $this->mealsStatusRepo->mealsAdd($id);
    }

    public function currentMealsRemove(int $id)
    {
        return $this->mealsStatusRepo->mealsRemove($id);
    }

    public function getVegetarianMeals()
    {   
        $meals = [];
        foreach($this->mealsRepo->getVegetarian() as $row) {
            $meals[] = $row->id;
        }
        return $meals;
    }

    public function active()
    {
        return $this->mealsRepo->getActive();
    }

    public function inactiveMeals()
    {
        return $this->mealsRepo->getInactive();
    }

    public function getBatch()
    {
        return $this->cycleRepo->getBatch();
    }
    
    public function saveMealStatusChange(int $cycleId, array $data): array
    {
        $data['meal_ids_add'] = isset($data['meal_ids_add']) ? $data['meal_ids_add'] : [];
        $data['meal_ids_remove'] = isset($data['meal_ids_remove']) ? $data['meal_ids_remove'] : [];
        
        $add = $data['meal_ids_add'];
        $new = [];
        foreach($add as $row) {
            if (in_array($row, $data['meal_ids_remove'])) {
                continue;
            }
            $new[] = $row;
        }
        $data['meal_ids_add'] = $new;
        if (count($this->mealsStatusRepo->get($cycleId)) <= 0)
            $this->mealsStatusRepo->store($data);
        else
            $this->mealsStatusRepo->update($data);

        
        // Update cycle default selections
        //$this->updateDefaulotSelections($cycleId);

        return [
            'status'    => 200,
            'success'   => true,
            'message'   => 'Successfully Saved!'
        ];
    }

    private function updateDefaultSelections(int $cycleId)
    {   
        $get = new Get($cycleId);

        list($meals, $mealsVeg) = $get->handle();
        $this->cycleRepo->updateDefaultSelections([
            'default_selections' => json_encode($meals),
            'default_selections_veg' => json_encode($mealsVeg),
            'id' => $cycleId,
        ]);
    }
    

}

