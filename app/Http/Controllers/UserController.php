<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'users' => User::all()]);
    }

    public function store(UserRequest $request)
    {
        try{
            $user = User::create([
                'name' => $request->get('name'),
                'lastname' => $request->get('lastname'),
                'email' => $request->get('email'),
                'username' => $request->get('username'),
                'password' => Hash::make($request->get('password')),
            ]);
            $token = JWTAuth::fromUser($user);
    
            return response()->json(compact('user','token'), 201);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el usuario' ], 500);
        }
    }
}
