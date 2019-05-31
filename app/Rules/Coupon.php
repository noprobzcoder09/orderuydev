<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Services\Coupons\Validator\Common;
use App\Services\Coupons\Validator\MinOrder;
use App\Services\Coupons\Validator\Onetime;
use App\Services\Coupons\Validator\Product;
use App\Services\Coupons\Validator\Solo;
use App\Services\Coupons\Validator\Taken;
use App\Services\Coupons\Validator\User;

use App\Services\Manageplan\Contracts\Order as OrderInterface;
use App\Services\Manageplan\Contracts\Coupon as CouponInterface;

class Coupon implements Rule
{   
    public $value;
    public $calllback;
    public $message = "The :attribute is invalid.";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userId = null, OrderInterface $order, CouponInterface $coupon)
    {
        $this->userId = $userId;
        $this->order = $order;
        $this->coupon = $coupon;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   
        // Check common validation such as used, expired date and exist
        $coupon = new Common($value);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }

        // Check for the duplicate codes
        // There should be no the same promo code entered
        // At the same time
        $coupon = new Taken($value, $this->coupon);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }


        // Check onetime use only
        // If the customer already use the code 
        // Then it should return an error
        $coupon = new Onetime($value, $this->userId);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }

        // Check for the solo code only
        // There should be no other promo code entered
        $coupon = new Solo($value, $this->coupon);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }

        // Check for the specific code for the product
        // It should not allow the code 
        // If it is belong on the specific product
        $coupon = new Product($value, $this->order);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }

        // Check for the specific code for the user
        // It should not allow the code 
        // If it is belong on the specific user
        $coupon = new User($value, $this->userId);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }

        // Check for the minimum order
        $coupon = new MinOrder($value, $this->order);
        if (!$coupon->valid()) {
            $this->message = $coupon->message;
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
