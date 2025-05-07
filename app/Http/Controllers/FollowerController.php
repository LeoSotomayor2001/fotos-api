<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\FollowNotification;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function store(User $user)
    {
        $user->followers()->attach(auth(guard:'api')->user()->id);
        // Notifica al usuario que ha sido seguido
        $user->notify(new FollowNotification(auth(guard:'api')->user()));
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
