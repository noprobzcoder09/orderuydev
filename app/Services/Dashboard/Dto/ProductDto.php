<?php

namespace App\Services\Dashboard\Dto;

Class ProductDto
{   
    public function __construct(
        $mealPlansId,
        $infusionsoftProductId, 
        $itemType, 
        $quantity, 
        $price, 
        $description, 
        $notes = ''
    ) {

        $this->mealPlansId = $mealPlansId;
        $this->infusionsoftProductId = $infusionsoftProductId;
        $this->itemType = $itemType;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->description = $description;
        $this->notes = $notes;
    }

    public function getMealPlansId()
    {
        return $this->mealPlansId;
    }

    public function getInfusionsoftProductId()
    {
        return $this->infusionsoftProductId;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getItemType()
    {
        return $this->itemType;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function getDescription()
    {
        return $this->description;
    }

}
