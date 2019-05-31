<?php

namespace App\Services\InfusionsoftV2\OAuth2;

use Infusionsoft\Infusionsoft;
use Illuminate\Http\Request;
use App\Repository\InfusionsoftAccountRepository;
use App\Services\InfusionsoftV2\OAuth2\API\RefreshAcessTokenProvider;
use Infusionsoft\Token as InfusionsoftToken;

Class OAuth2Service
{   
    use RefreshAcessTokenProvider;

    public function __construct(\App\Repository\InfusionsoftAccountRepository $repository)
    {
        $this->infussion = new Infusionsoft(config('infusionsoft'));    
        $this->repo = $repository;
    }

    public function getAuthorizationUrl(){

        return $this->infussion->getAuthorizationUrl();
    }

    public function storeAccessTokenByCode(string $code)
    {
        $this->repo->updateCurrentActiveAccountToken(
            $this->infussion->requestAccessToken($code)
        );
    }

    public function storeAccessTokenByTokenInterface(InfusionsoftToken $token)
    {
        $this->repo->updateCurrentActiveAccountToken(
            $token
        );
    }

    public function setRequestToken()
    {   
        $this->infussion->setToken(
            new InfusionsoftToken(
                $this->repo->getCurrentActiveAccountTokenInArray(env('APP_ENV'))
            )
        );
        $this->shouldRefreshTokenIfExpired(); 
    }

    public function refreshAccessToken()
    {
        $this->infussion->refreshAccessToken();   
    }

    public function isTokenExpired()
    {
       return $this->infussion->isTokenExpired();
    }

    public function setToken($token)
    {
        $this->infussion->setToken($token);
    }

    public function getToken()
    {
        return $this->infussion->getToken();
    }

    public function refillEmptyFields(array $fieldsNeedToFill = array(), array $data = array())
    {
        foreach($fieldsNeedToFill as $field) {
            if (isset($data[$field]) || empty($data[$field])) {
                $data[$field] = "&nbsp";
            }
        }

        return $data;
    }

}
