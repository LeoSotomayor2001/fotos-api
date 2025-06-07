<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class PostController extends Controller
{

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $posts = $this->postService->getFollowingPosts($request);

        return response()->json([
            'posts' => $posts,
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
        $data = $request->validated();
        $post = $this->postService->updatePost($data, $id);
        if ($post instanceof JsonResponse) {
            return $post; // En caso de error, retorna la respuesta JSON del servicio.
        }
        return response()->json([
            'message' => 'Publicación actualizada exitosamente',
        ]);
    }

    public function destroy(string $id)
    {
        $post = $this->postService->deletePost($id);
        if ($post instanceof JsonResponse) {
            return $post; // En caso de error, retorna la respuesta JSON del servicio.
        }
        return response()->json([
            'message' => 'Publicación eliminada exitosamente'
        ]);
    }
}
