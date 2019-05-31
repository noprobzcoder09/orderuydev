<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfusionsoftSync extends Model
{   
    use SoftDeletes;

    protected $table = 'infusionsoft_sync';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field', 'old_value','new_value','status',
        'contacts_updated','started_at','finished_at',
        'group','admin_id'
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
}
