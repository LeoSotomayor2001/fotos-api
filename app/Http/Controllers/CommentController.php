<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Gate;


class CommentController extends Controller
{
    public function store(CommentRequest $request){
        
        $comentario=Comment::create($request->all());
        $publicacion=Post::find($request->post_id);
        // Notifica al usuario que ha comentado en su publicación
        if(auth(guard:'api')->user()->id != $publicacion->user_id){
            $publicacion->user->notify(new CommentNotification($publicacion,$comentario));
            
        }
        return response()->json('Comentario creado correctamente',200);
    }

    public function update(CommentRequest $request, Comment $comment){
        Gate::authorize('update',$comment);
        $comment->update($request->all());

        return response()->json('Comentario actualizado correctamente',200);
    }

    public function destroy(Comment $comment){
        Gate::authorize('delete',$comment);
        try{
            $comment->delete();
            return response()->json('Comentario eliminado correctamente',200);
        }
        catch(\Exception $e){
            return response()->json('Error al eliminar el comentario',500);
        }
    }
}
