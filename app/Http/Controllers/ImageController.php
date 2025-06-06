<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($filename)
    {
        $imageURL = "/perfiles/" . $filename ;
        // Verifica si la imagen existe
        if (!Storage::disk('public')->exists($imageURL)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Devuelve la imagen
        return response()->file(Storage::disk('public')->path($imageURL));
    }

    public function showPost($filename)
    {
        $imageURL = "/posts/" . $filename ;
        // Verifica si la imagen existe
        if (!Storage::disk('public')->exists($imageURL)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Devuelve el post
        return response()->file(Storage::disk('public')->path($imageURL));
    }
}
