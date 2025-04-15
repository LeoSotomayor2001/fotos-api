<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index() {}

    public function store(PostRequest $request)
    {
        // Validación ya está en PostRequest
        $data = $request->validated(); // Usar validated() en lugar de all()

        // Crear el post primero con los datos básicos
        $post = Post::create($data);

        // Manejar el archivo si existe
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->getSize() > 100 * 1024 * 1024) { // 100MB
                return response()->json([
                    'error' => 'El archivo excede el tamaño máximo permitido de 100MB'
                ], 422);
            }

            // Almacena el archivo y obtén solo el nombre
            $fileName = $file->storeAs('posts', $file->hashName(), 'public');
            $fileName = basename($fileName); // Obtiene solo el nombre del archivo

            $post->update([
                'file' => $fileName, // Guarda solo 'asdasda.ext'
                'file_type' => str($file->getMimeType())->before('/')
            ]);
        }


        return response()->json([
            'message' => 'Post creado exitosamente',
            'post' => $post
        ], 201);
    }
}
