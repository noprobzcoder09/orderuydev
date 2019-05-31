<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = ['title','description','action_by','ip_address','country','device_name','platform_name','browser_name','browser_version', 'additional_data'];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
    	return $this->hasOne(\App\Models\Users::class, 'id', 'action_by');
    }
}
