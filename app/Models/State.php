<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{   
    use SoftDeletes;

    protected $table = 'state';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state_code', 'country_id','state'
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

     /**
     * Get the country that owns the state.
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }
    
}
