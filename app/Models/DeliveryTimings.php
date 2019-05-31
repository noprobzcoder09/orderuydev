<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryTimings extends Model
{   
    use SoftDeletes;

    protected $table = 'delivery_timings';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'delivery_day', 'cutoff_day', 'cutoff_time','disabled','infs_cutoff_time'
    ];

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
        return $this->belongsTo('App\Models\DeliveryZoneTimings');
    }
    
}
