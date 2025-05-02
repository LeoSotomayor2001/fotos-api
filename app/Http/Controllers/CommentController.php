<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request){
        
        Comment::create($request->all());

        return response()->json('Comentario creado correctamente',200);
    }

    public function update(CommentRequest $request, Comment $comment){
        $comment->update($request->all());

        return response()->json('Comentario actualizado correctamente',200);
    }

    public function destroy(Comment $comment){
        try{
            $comment->delete();
            return response()->json('Comentario eliminado correctamente',200);
        }
        catch(\Exception $e){
            return response()->json('Error al eliminar el comentario',500);
        }
    }
}
