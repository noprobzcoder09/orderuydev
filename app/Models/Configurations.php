<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{      

    protected $table = 'configurations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'name','value'
    ];


    public function getActiveBatch()
    {
        return $this->where(['slug' => 'active-cycle-batch'])->first()->value ?? '';
    }

    public function getlastActiveBatch()
    {
        return (int)$this->getActiveBatch() - 1;
    }
    
    public function setActivebatch($batch)
    {
        return $this->where(['slug' => 'active-cycle-batch'])
                    ->update(['value' => $batch]);
    }

    public function getAdminEmails()
    {
        return $this->where(['slug' => 'report_admin_email'])->first()->value ?? '';
    }

    public static function getReportErrorInfsApiAdminEmails()
    {
        return self::where('slug', 'report-error-api')->first()->value ?? '';
    }

    public static function getManageSubscriptionText()
    {
        return self::where('slug', 'manage-subscription-text')->first()->value ?? '';
    }
}
