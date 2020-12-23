<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $credentials;

    function __construct(Request $request)
    {
        $token = $request->bearerToken();

        $this->credentials = $token ? JWT::decode($token, env('JWT_SECRET'), ['HS256']) : null;
    }
}
