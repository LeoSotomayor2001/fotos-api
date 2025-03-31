<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    
    public function __invoke(Request $request)
    {
        try {
            auth(guard:'api')->logout();
            return response()->json(['message' => 'Sesión cerrada correctamente']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }
    }
}
