<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogCredentials extends Model
{
    protected $table = 'activity_log_credentials';

    protected $fillable = ['deletion_password'];
}

