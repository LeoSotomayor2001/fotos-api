<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Gate;

class PostService
{
      public function getFollowingPosts(Request $request): ResourceCollection
    {
        $authUser = $request->user(); // ✅ Obtener usuario autenticado

        // Obtener los IDs de los usuarios que sigue
        $followingIds = $authUser->followings()->select('users.id')->pluck('id');

        // Obtener los posts de esos usuarios y ordenarlos por fecha
        $posts = Post::whereIn('user_id', $followingIds)
            ->latest()
            ->get();

        return PostResource::collection($posts); // ✅ Retornar colección de recursos
    }
    public function createPost(array $data, Request $request)
    {
        // Crear el post con los datos básicos
        $post = Post::create($data);

        // Manejar el archivo si existe
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->getSize() > 100 * 1024 * 1024) { // 100MB
                return response()->json([
                    'error' => 'El archivo excede el tamaño máximo permitido de 100MB'
                ], 422);
            }

            // Almacenar el archivo y obtener el nombre
            $fileName = $file->storeAs('posts', $file->hashName(), 'public');
            $fileName = basename($fileName);

            $post->update([
                'file' => $fileName,
                'file_type' => str($file->getMimeType())->before('/')
            ]);
        }
    }

    public function updatePost(array $data, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Publicación no encontrada'
            ], 404);
        }

        Gate::authorize('update', $post);

        $post->update($data);

        return $post;
    }

    public function deletePost(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'error' => 'Publicación no encontrada'
            ], 404);
        }
        Gate::authorize('delete', $post);

        $post->delete();
    }
}
