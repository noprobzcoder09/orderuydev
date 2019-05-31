<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryZone extends Model
{   

    use SoftDeletes;
    
    protected $table = 'delivery_zones';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zone_name',
        'delivery_address',
        'disabled'
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
    public function zoneTiming()
    {
        return $this->hasMany('App\Models\DeliveryZoneTimings');
    }

     /**
     * Get all of the posts for the country.
     */
    public function timings()
    {
        return $this->select([
            'delivery_zone_timings.id as delivery_zone_timings_id',
            'cycles.delivery_timings_id',
            'delivery_timings.id',
            'cycles.delivery_date',
            'cycles.cutover_date',
            'cutoff_time'
        ])
        ->join('delivery_zone_timings',
                'delivery_zone_timings.delivery_zone_id','=','delivery_zones.id'
            )
        ->join('delivery_timings',
                'delivery_timings.id','=','delivery_zone_timings.delivery_timings_id'
            )
        ->join('cycles',
                'cycles.delivery_timings_id','=','delivery_zone_timings.delivery_timings_id'
            )
        ->where('delivery_zones.disabled',0)
        ->where('delivery_timings.disabled',0)
        ->where('cycles.status',1)
        ->where('delivery_zone_timings.deleted_at',NULL);
    }
    
}
