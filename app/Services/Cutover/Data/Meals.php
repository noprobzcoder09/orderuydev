<?php

namespace App\Services\Cutover\Data;

use App\Models\Meals as Model;

Class Meals
{   
    public function __construct()
    {
        $this->meals = new Model;
    }

    public function updateStatus(int $id, $status)
    {
         return $this->meals->where('id', $id)
        ->update([
            'status' => $status
        ]);
    }

    public function isVegetarian(int $id)
    {
        return $this->meals->where(['id' => $id, 'vegetarian' => true])->count() > 0;
    }
    
}
