<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{   
    use SoftDeletes;

    protected $table = 'coupons';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_code', 'discount_type', 'discount_value', 'used', 'user', 'expiry_date', 'products', 'min_order', 'max_uses', 'number_used','solo','onetime','recur'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
}
