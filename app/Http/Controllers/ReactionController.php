<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    
    public function store(Request $request){
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|in:like,love,haha,sad,angry',
        ]);
    
        $reaction = Reaction::updateOrCreate(
            ['user_id' => auth(guard:'api')->user()->id, 'post_id' => $request->post_id],
            ['type' => $request->type]
        );
    
        return response()->json(['message' => 'Reacción guardada correctamente', 'reaction' => $reaction]);
    }
}
