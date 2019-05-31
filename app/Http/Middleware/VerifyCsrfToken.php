<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'get-deliverytime-byzone',
        '/order/addtocart',
        'email-verify',
        'auth/ajax/login',
        'auth/ajax/logout',
        'auth/account',
        'order/checkout',
        'order/coupon/enter',
        'order/summary',
        'order/coupon/delete'
    ];
}
