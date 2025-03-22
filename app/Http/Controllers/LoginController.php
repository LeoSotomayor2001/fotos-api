<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    
    public function login(Request $request) :JsonResponse
    {
        $credentials=$request->only('email','password');
        try{

            if(!$token=auth(guard:'api')->attempt($credentials)){
                return response()->json(['error'=>'Unauthorized'],401);
            }
            $user=auth(guard:'api')->user();
            return response()->json([
                'message' => 'Login successful!',
                'token' => $token,
                'user' => $user
            ], 200);
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }
}
