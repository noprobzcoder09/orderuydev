<?php namespace App\Http\Controllers\Api;

use App\Models\ActivityLogCredentials as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityLogCredential extends ApiController
{

    public function authenticate(Request $request)
    {
        $hash = Model::first();

        $password = isset($request->password)? $request->password : '';

        if (!password_verify($password, $hash->deletion_password)){

            return $this->respondUnprocessable('Log Deletion has been failed. Incorrect Password!');

        }

        (new \App\Models\AuditLog)->find($request->id)->delete();

        return $this->respondSuccessfulWithData('Log has been deleted.', []);
    }

}


