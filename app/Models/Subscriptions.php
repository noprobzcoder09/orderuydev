<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriptions extends Model
{
    use SoftDeletes;
    
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','meal_plans_id','status','paused_till',
        'price','quantity','ins_order_id','ins_invoice_id','cancelled_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the selections for the Subscriptions.
     */
    public function selections()
    {
        return $this->hasOne('App\Models\SubscriptionsSelections');
    }

    /**
     * This Subscription has one meal plan
     * @return Eloquent
     */
    public function meal_plan()
    {
        return $this->hasOne(\App\Models\MealPlans::class, 'id', 'meal_plans_id');
    }
    
}
