<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
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
}
