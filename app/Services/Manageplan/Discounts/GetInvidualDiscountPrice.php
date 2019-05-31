<?php

namespace App\Services\Manageplan\Discounts;

Class GetInvidualDiscountPrice
{   
    public $order;
    public $coupon;
    public $couponPrice = array();

    const fixed = 'fixed';
    const percent = 'percent';

    public function __construct($order, $coupon)
    {
        $this->order = $order;
        $this->coupon = $coupon;

        $this->parse();
    }

    public function getAll() 
    {
        return $this->couponPrice;
    }

    public function getRecurring() 
    {   
        $couponPrice = $this->couponPrice;
        $recurring = array_map(function($item) use ($couponPrice) {
            $item = is_array($item) ? (object)$item : $item;
            if ($item->recur == 1) {
                return array (
                    'code' => $item->code,
                    'total' => $couponPrice[$item->code]['total'] ?? 0,
                    'name' => $couponPrice[$item->code]['name'] ?? '',
                    'discountValue' => $couponPrice[$item->code]['discountValue'] ?? '',
                );
            }
        }, $this->coupon->get());

        return array_values(array_filter(($recurring)));
    }

    public function getTotalDiscount() 
    {
       $total = array_reduce($this->couponPrice, function($total, $discount) {
            return $total += $discount['total'];
        });

       return is_null($total) ? 0 : $total;
    }

    public function getTotalRecurring() 
    {
        $recurring = array_map(function($item) {
            $item = is_array($item) ? (object)$item : $item;
            if ($item->recur == 1) {
                return $item;
            }
        }, $this->coupon->get());

        $couponPrice = $this->couponPrice;
        
        $total = array_reduce($recurring, function($total, $item) use ($couponPrice) {
            $item = is_array($item) ? (object)$item : $item;
            if (isset($item->code)) {
                $total += $couponPrice[$item->code]['total'] ?? 0;
            }
            return $total;
        });

        return is_null($total) ? 0 : $total;
    }

    private function parse()
    {
        foreach($this->coupon->get() as $coupon) {
            $coupon = is_array($coupon) ? (object)$coupon : $coupon;  
            $type = strtolower($coupon->type);

            $product = $coupon->products;
            
            if (empty($product)) {
                $this->parseAll($type, $coupon->code, $coupon->discount);
            } else {
                $this->parseByProduct($product, $type, $coupon->code, $coupon->discount);
            }
        }
    }

    private function parseAll(string $type, string $code, $discount)
    {   
        $price = 0;
        $discountTotal = 0;
        foreach($this->order->get() as $planId =>  $row) {
            $row = is_array($row) ? (object)$row : $row;
            $price += $row->price;
        }

        if ($type == self::fixed) {
            $discountValue = $discount;
            $discountTotal = ($price - ($price - $discountValue));
        }
        elseif ($type == self::percent) {
            $discountValue = $discount/100;
            $discountTotal = $price-($price - ($price * $discountValue));
        }
        $this->couponPrice[$code] = array(
            'code' => $code,
            'total' => $discountTotal,
            'name' => '',
            'discountValue' => (($type == self::percent) ? '' : __('config.currency')).$discount.(($type == self::percent) ? '%' : '')
        );
    }

    private function parseByProduct(array $product, string $type, string $code, $discount)
    {   
        $price = array();
        $plan = array();
        $discountTotal = 0;
        foreach($this->order->get() as $planId =>  $row) {
            $row = is_array($row) ? (object)$row : $row;
            if (in_array($planId, $product)) {
                $price[] = $row->price;
                $plan[] = $row->name;
            }
        }

        $discountTotal = array_reduce($price, function($discountTotal, $price) use ($type, $discount) {
            if ($type == self::fixed) {
                $discountValue = $discount;
                $discountTotal += ($price - ($price - $discountValue));
            }
            elseif ($type == self::percent) {
                $discountValue = $discount/100;
                $discountTotal += $price-($price - ($price * $discountValue));
            }
            return $discountTotal;
        });

        $this->couponPrice[$code] = array(
            'code' => $code,
            'total' => $discountTotal,
            'name' => '('.implode(',', $plan).')',
            'discountValue' => (($type == self::percent) ? '' : __('config.currency')).$discount.(($type == self::percent) ? '%' : '')
        );
    }
}
