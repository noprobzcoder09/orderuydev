<?php

namespace App\Services\Cutover\Dto;

Class Product
{   
    public function __construct(array $products) {
        $this->products = $products;

        $this->parse();
    }

    public function getProducts()
    {
        return $this->products;
    }

    private function parse()
    {
        $products = array();
        foreach($this->products as $row) {
            $row = is_array($row) ? (object)$row : $row;
            array_push($products, array(
                'infusionsoftProductId' => $row->infusion_product_id,
                'price' => $row->price,
                'itemType' => (int)env('PRODUCT_ITEMTYPE'),
                'quantity' => $row->quantity,
                'notes' => '',
                'description' => $row->name,
                'subscription_id' => $row->subscription_id
            ));
        }

        $this->products = $products;
    }

}
