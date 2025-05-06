<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use App\Notifications\LikeNotification;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    
    public function store(Request $request){
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|in:like,love,haha,sad,angry',
        ]);
        $user= auth(guard:'api')->user();
        $post=Post::find($request->post_id);

        $reaction = Reaction::updateOrCreate(
            ['user_id' => auth(guard:'api')->user()->id, 'post_id' => $request->post_id],
            ['type' => $request->type]
        );

        // Notificar al usuario que ha reaccionado a su publicación
        $post->user->notify(new LikeNotification($user, $post));
    
        return response()->json(['message' => 'Reacción guardada correctamente', 'reaction' => $reaction]);
    }

    public function destroy(Request $request){
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);
    
        $reaction = Reaction::where('user_id', auth(guard:'api')->user()->id)
                            ->where('post_id', $request->post_id)
                            ->first();
        
        if ($reaction) {
            $reaction->delete();
            return response()->json(['message' => 'Reacción eliminada correctamente']);
        }
    
        return response()->json(['message' => 'No has reaccionado a esta publicación'], 404);
    }
}
