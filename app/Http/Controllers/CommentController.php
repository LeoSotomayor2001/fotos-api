<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request){
        
        Comment::create($request->all());

        return response()->json('Comentario creado correctamente',200);
    }
}
