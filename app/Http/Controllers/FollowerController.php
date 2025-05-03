<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function store(User $user)
    {
        $user->followers()->attach(auth(guard:'api')->user()->id);
        return response()->json([
            'message' => 'Siguiendo a ' . $user->username,
        ]);
    }
    public function destroy(User $user)
    {
        // Desvincula al usuario autenticado del usuario que desea dejar de seguir
        $user->followers()->detach(auth(guard:'api')->user()->id);
    
        return response()->json([
            'message' => 'Dejaste de seguir a ' . $user->username,
        ]);
    }
}
