<?php

namespace App\Http\Controllers;

use App\Helpers\JWTHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class JWTController extends Controller
{
    public function generateJWTToken() {
        $token = JWTHelper::generateToken();
        return $token;
    }

    public function getJWTToken()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $user = Auth::user();
        $jwtToken = Session::get('jwt_token');

        // Generate new token if not exists or expired
        if (!$jwtToken || JWTHelper::isTokenExpired($jwtToken)) {
            $jwtToken = JWTHelper::generateToken($user);
            // session(['jwt_token' => $jwtToken]);
            Session::put('jwt_token', $jwtToken);
        }

        // Create redirect token
        $redirectToken = JWTHelper::generateRedirectToken($jwtToken);
        return response()->json([
            'success' => true,
            'redirect_token' => $redirectToken
        ]);
    }
}