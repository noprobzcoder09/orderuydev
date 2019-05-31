<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionsSelections extends Model
{
    use SoftDeletes;
    
    protected $table = 'subscriptions_cycles';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','cycle_id','subscription_id',
        'menu_selections','cycle_subscription_status',
        'delivery_zone_id','ins_invoice_id','discount_id',
        'cancelled_at'
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
     * Get the subscriptions for the selections.
     */
    public function subscriptions()
    {
        return $this->belongsTo('App\Models\Subscriptions', 'subscription_id');
    }

    /**
     * Get the subscriptions for the selections.
     */
    public function getLatest(int $userId)
    {
        return $this->where('user_id', $userId)->orderBy('id','desc')->first();
    }
    
}
