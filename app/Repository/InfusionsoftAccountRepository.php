<?php

namespace App\Repository;

use Session;

use App\Models\InfusionsoftAccount;
use App\Rules\Custom;
use Carbon\Carbon;

Class InfusionsoftAccountRepository
{   
    public function __construct() 
    {
        $this->model = new InfusionsoftAccount;
    }


    public function updateCurrentActiveAccountToken(\Infusionsoft\Token $token)
    {   
        $data = $this->model->retrieveCurrentInfsAccount();
        $model = $this->model->find($data->id);

        $model->access_token = $token->accessToken;
        $model->refresh_token = $token->refreshToken;
        $model->expires_in = Carbon::createFromTimestamp($token->endOfLife);
        $model->scope = $token->extraInfo['scope'];
        $model->token_type = $token->extraInfo['token_type'];
        $model->save();
    }

    public function getCurrentActiveAccountToken($appName = null)
    {   
        $appName = empty($appName) ? env('APP_ENV') : $appName;
        return $this->model->getInfsAccount($appName);
    }

    public function getCurrentActiveAccountTokenInArray($appName = null)
    {   
        $token = $this->getCurrentActiveAccountToken($appName);
        return [
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_in' =>  Carbon::parse($token->expire_date)->timestamp,
            "token_type" => $token->token_type,
            "scope"=> $token->scope
        ];
    }
}
