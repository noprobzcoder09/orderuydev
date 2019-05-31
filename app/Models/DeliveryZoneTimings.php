<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryZoneTimings extends Model
{   
    use SoftDeletes;

    protected $table = 'delivery_zone_timings';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'delivery_zone_id', 'delivery_timings_id'
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
     * Get the state for the Country.
     */
    public function zone()
    {
        return $this->belongsToMany('App\Models\DeliveryZone');
    }
    
    /**
     * Get the state for the Country.
     */
    public function timings()
    {
        return $this->join('delivery_timings',
            'delivery_timings.id','=','delivery_zone_timings.delivery_timings_id'
        )
        ->select(['delivery_timings.id','cutoff_day','cutoff_time','delivery_day']);
    }
}
