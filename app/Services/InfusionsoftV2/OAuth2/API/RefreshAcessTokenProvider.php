<?php

namespace App\Services\InfusionsoftV2\OAuth2\API;

use Carbon\Carbon;
use App\Models\InfusionsoftAccount;

Trait RefreshAcessTokenProvider
{   
   
   public function shouldRefreshTokenIfExpired()
   {    
        // if (!$this->verifyRestHook()) {
        if ($this->verifyIfExpired()) {
            \Log::info('Requesting New Token...');
            $this->refreshAccessToken();
            $this->storeAccessTokenByTokenInterface(
                $this->getToken()
            );
        }
   }
    
   public function verifyRestHook()
   {    
        try
        {
            $resthooks = $this->infussion->resthooks();
            // first, create a new task
            $resthook = $resthooks->create([
                'eventKey' => 'contact.add',
                'hookUrl' => 'http://infusionsoft.app/verifyRestHook.php'
            ]);
            
            $resthook = $resthooks->find($resthook->id)->verify();
            
            return $resthook;
        }
        catch(\Infusionsoft\TokenExpiredException $e)
        {
            return false;
        }
        catch(\GuzzleHttp\Exception\ClientException $e)
        {
            return false;
        }
        catch(\Infusionsoft\Http\HttpException $e)
        {
            return false;
        }
        
        catch(\Exception $e)
        {
            return false;
        }
   }

   public function verifyIfExpired()
   {
        $env_type = strtolower(env('APP_ENV'));

        $account = InfusionsoftAccount::getInfsAccount($env_type);

        if( $account->expires_in <= Carbon::now()->addHour() ){
            return true;         
        }

        return false;
   }
}
