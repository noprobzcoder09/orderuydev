<?php

namespace App\Services;


class Helper
{   

    const token_length = 20;

    public static function encodeToken($token) {
        if (!$token) return;
        $key = sha1('ab$6*1hVmkLd^0.');
        $code1 = substr($key,0,strlen($key)-self::token_length);       
        $code2 = substr($key,strlen($key)-self::token_length,self::token_length);
        $key = $code1.$token.$code2.'|'.base64_encode(strlen($code2.$token));

        return base64_encode($key);
    }

    
    public static function decodeToken($token) {
        if (!$token) return;
        $token_ = base64_decode($token);
        
        $token_ = explode('|',$token_);
        $tokenLeftLength = base64_decode($token_[1]);
        $token_ = $token_[0];

        $key = substr($token_, -$tokenLeftLength);

        return substr($key,0,strlen($key)-self::token_length);
    }

    public static function _print_r($data)
    {
        echo "<pre>";
        print_r($data);
        echo "<pre>";
    }

    public static function getAppLink()
    {
        $env = strtolower(env('APP_ENV'));
        $link  = env('INFS_APP_URL');
        if ($env == 'live') {
            $link = env('LIVE_INFS_APP_URL');
        }
        return $link;
    }

    public static function success(string $message = '', array $contents = array(), string $code = '200')
    {
        return self::getArrayResponse(true, $message, $contents, $code);
    }

    public static function failed(string $message = '', array $contents = array(), string $code = '200')
    {
        return self::getArrayResponse(false, $message, $contents, $code);
    }

    private static function getArrayResponse(bool $success, string $message, array $contents = array(), string $code = '200')
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'code' => $code
        ];

        if (!empty($contents)) {
            $response = array_merge($response, $contents);
        }
        
        return $response;
    }
}
