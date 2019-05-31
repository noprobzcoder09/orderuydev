<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class UserDetails extends Model
{
    use SoftDeletes;
    
    protected $table = 'user_details';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','first_name','last_name','mobile_phone','delivery_notes','dietary_notes',
        'delivery_zone_timings_id','ins_contact_id','card_ids','default_card',
        'billing_first_name','billing_last_name','billing_mobile_phone','status',
        'billing_attempt','billing_attempt_desc'
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
     * Get Details
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\Users','user_id','');
    }

    public function getDetailsIdByUser(int $userId)
    {
        return $this->where('user_id', $userId)->first()->id;
    }

    public function email(int $userId)
    {
        return (new \App\Models\Users)->email($userId);
    }

    public function getAllCustomer()
    {
        return $this->where('role','customer')
        ->join('users','users.id','=','user_details.user_id');
    }

    public function getAllUsers()
    {
        return $this->join('users','users.id','=','user_details.user_id');
    }

    public function gerSubscriptions()
    {   
        return DB::table('user_details')->select([
            'user_details.user_id',
            'subscriptions.status',
            DB::raw("concat(user_details.first_name,' ',user_details.last_name) as name"),
            'first_name',
            'last_name',
            'plan_name',
            'mobile_phone',
            'meal_plans.vegetarian',
            'delivery_timings.delivery_day',
            'delivery_timings.cutoff_day',
            'delivery_timings.cutoff_time',
            'delivery_zones.zone_name',
            'delivery_timings.id as delivery_timings_id',
            'delivery_zones.id as delivery_zone_id',
            'subscriptions.meal_plans_id',
            'subscriptions.paused_till',
            'subscriptions.id',
            'subscriptions_cycles.cycle_id',
            'user_details.status as user_status'

        ])
         ->join('subscriptions','subscriptions.user_id','=','user_details.user_id')
         ->join('subscriptions_cycles', function($join) {
            $join->on('subscriptions_cycles.subscription_id','=','subscriptions.id');
            // ->where('subscriptions_cycles.cycle_subscription_status','active');
         })
         ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
         ->join('delivery_timings','delivery_timings.id','=','cycles.delivery_timings_id')
         ->join('delivery_zones','delivery_zones.id','=','subscriptions_cycles.delivery_zone_id')
        ->join('meal_plans','meal_plans.id','=','subscriptions.meal_plans_id')
        ->orderBy('user_details.last_name','asc');
    }
    
}
