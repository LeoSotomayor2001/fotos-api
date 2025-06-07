<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $authUser = $request->user(); // ✅ Obtener usuario autenticado

        // Obtener los IDs de los usuarios que sigue
        $followingIds = $authUser->followings()->select('users.id')->pluck('id');


        // Obtener los posts de esos usuarios y ordenarlos por fecha
        $posts = Post::whereIn('user_id', $followingIds)
            ->latest() // ✅ Ordenar por fecha de creación
            ->get();

        return response()->json([
            'posts' => PostResource::collection($posts),
        ]);
    }


    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $this->postService->createPost($data, $request);
        return response()->json([
            'message' => 'Post creado exitosamente',
        ], 201);
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'error' => 'Publicación no encontrada'
            ], 404);
        }
        return response()->json(new PostResource($post));
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Publicación no encontrada'
            ], 404);
        }
        Gate::authorize('update', $post);

        $post->update($request->all());

        return response()->json([
            'message' => 'Publicación actualizada exitosamente',
        ]);
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'error' => 'Publicación no encontrada'
            ], 404);
        }
        Gate::authorize('delete', $post);
        $post->delete();
        return response()->json([
            'message' => 'Publicación eliminada exitosamente'
        ]);
    }
}
