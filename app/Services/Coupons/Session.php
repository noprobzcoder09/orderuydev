<?php

namespace App\Services\Coupons;

use App\Services\Coupons\AbstractCouponsFactory;


Class Session implements AbstractSessionCoupon
{   
    public function __construct(\App\Services\Session\AdapterInterface $session)
    {
    	$this->session = $session;
    }

	public function store(array $coupon) {  
        $this->data($data);  

        $data['coupons'][] = $coupon;

        $this->session->store($data);
    }

    private function data(&$data)
    {
    	$data = $this->session->get();
        if (empty($data)) {
            $data = [];
        }
        return $data;
    }

    public function get() {  
        return $this->session->get()['coupons'] ?? [];
    }

    public function delete(string $code)
    {   
        $coupon = $this->get();
        foreach($coupon as $key => $row) {
            if ($code === $row['code']) {
                unset($coupon[$key]);
            }
        }
        $data['coupons'] = $coupon;
        $this->restore($data);
    }

    private function restore(array $coupon) {  
        $this->session->store($coupon);
    }

    public function destroy()
    {
        $this->session->destroy();
    }
}
