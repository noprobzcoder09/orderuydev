<?php

namespace App\Models;

use App\Notifications\ResetPassword;

use illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


use Auth;
use Request;

class Users extends  Authenticatable
{

    use SoftDeletes, Notifiable;
    
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verification','active','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get Details
     *
     * @return object
     */
    public function details()
    {
        return $this->hasOne('\App\Models\UserDetails','user_id');
    }

    public function email(int $userId)
    {
        $email = $this->where($this->primaryKey, $userId)->first();
        return isset($email->email) ? $email->email : '';
    }

    public function verifyEmail(string $email)
    {
        return $this->where('email', $email)->count() > 0;
    }

    public function getIdByEmail(string $email)
    {
        $d = $this->where('email', $email)->first();
        return isset($d->id) ? $d->id : 0;
    }

    public function isAdmin(int $id = null) {
        if (empty($id)) {
            $id = Auth::id();
        }
        $d = $this->where('id',$id)->select('role')->where('role','administrator')->first();
        if(isset($d->role)) {
            return !empty($d);
        }
        return false;
    }

    public function isRestrictedAdmin(int $id = null) {
        if (empty($id)) {
            $id = Auth::id();
        }
        $d = $this->where('id',$id)->select('role')->where('role','restricted admin')->first();
        if(isset($d->role)) {
            return !empty($d);
        }
        return false;
    }    

    public function isCustomer(int $id = null) {
        if (empty($id)) {
            $id = Auth::id();
        }
        $d = $this->where('id',$id)->select('role')->where('role','customer')->first();
        if(isset($d->role)) {
            return !empty($d);
        }
        return false;
    }

    public function isDeveloper(int $id = null)
    {
        if (empty($id)) {
            $id = Auth::id();
        }
        $d = $this->where('id',$id)->select('role')->where('role','developer')->first();
        if(isset($d->role)) {
            return !empty($d);
        }
        return false;
    }

    public function accessDenied() {
        if (Request::ajax()) {
            return [
                'code' => __('codes.accessDenied'),
                'message' => __('config.access-denied'),
                'success' => false
            ];
        }
        abort(401,__('config.access-denied'));
    }


    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token, $this));
    }

}
