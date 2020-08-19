<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'user_id' => Auth::id(),
            'usernames' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
