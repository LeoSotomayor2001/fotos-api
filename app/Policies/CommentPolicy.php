<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Comment $comment): Response
    {
        return $user->id === $comment->user_id ? Response::allow()
        : Response::deny('No tienes permiso de editar este comentario.');
    }
    public function delete(User $user, Comment $comment): Response
    {
        return $user->id === $comment->user_id ? Response::allow()
        : Response::deny('No tienes permiso de eliminar este comentario.');
    }
}
