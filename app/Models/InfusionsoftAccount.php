<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfusionsoftAccount extends Model
{   
    use SoftDeletes;

    protected $table = 'infusionsoft_accounts';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_name', 'version','legacy_key',
        'client_id','client_secret','access_token',
        'refresh_token','expires_in','scope',
        'redirect_url','environment'
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
    * Retrieve legacy credentials to be used in setting the correct API key. 
    * You can change this accordingly (i.e. use the userId to determine the credential)
    *
    * @return array(app_name, 'legacy_key')
    */
    public static function retrieveLegacyCredentials() 
    {
        $infsAccount = self::where('environment', env('APP_ENV'))->first();
        if(!$infsAccount) die("No credential for the environment: ". env('APP_ENV'));
        return ['app_name' => $infsAccount['app_name'], 'legacy_key' => $infsAccount['legacy_key']];
    }

    /**
    * Retrieve oauth credentials to be used in setting the correct oauth credential. 
    * You can change this accordingly (i.e. use the userId to determine the credential)
    *
    * @return array(client_id, client_secret, redirect_url)
    */
    public static function retrieveOauthCredentials() 
    {
        $infsAccount = self::where('environment', env('APP_ENV'))->first();
        if(!$infsAccount) die("No credential for the environment: ". env('APP_ENV'));
        return ['client_id' => $infsAccount['client_id'], 'client_secret' => $infsAccount['client_secret'], 'redirect_url' => $infsAccount['redirect_url']];
    }

    /**
    * Retrieve entire row of InfsAccount. This will be used to setting token during API call via oauth
    * You can change this accordingly (i.e. use the userId to determine the row)
    *
    * @return array(client_id, client_secret, redirect_url)
    */
    public static function retrieveCurrentInfsAccount() 
    {
        return self::where('environment', env('APP_ENV'))->first();
    }

    public static function getInfsAccount($appName = '') 
    {
        return self::where('environment', $appName)->first();
    }
}
