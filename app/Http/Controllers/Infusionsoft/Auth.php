<?php

namespace App\Http\Controllers\Infusionsoft;

use App\Http\Controllers\Controller;

use App\Services\InfusionsoftV2\InfusionsoftFactory;

use Illuminate\Http\Request;

class Auth extends Controller
{
    public function __construct()
    {
        $this->infusionsoft = (new InfusionsoftFactory('oauth2'))->authenticate();
    }

    public function authenticate()
    {
        return redirect($this->infusionsoft->getAuthorizationUrl());
    }

    public function callbacktoken(Request $request)
    {
        $this->infusionsoft->storeAccessTokenByCode($request->get('code'));

        return redirect('auth-success')->with('status_code', 200);
    }
}
