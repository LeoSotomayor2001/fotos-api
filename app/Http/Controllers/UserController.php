<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'users' => UserResource::collection(User::all())
        ]);
    }

    public function store(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = JWTAuth::fromUser($user);
        $success = true;

        return response()->json(compact('user', 'token','success'), 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        
        try {
            $user = User::findOrFail($id);
            // Solo actualiza los campos que vienen en la solicitud
            $user->fill($request->only(['name', 'lastname', 'email', 'username', 'password']));

            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }

             // Manejar la actualización de la imagen
             if ($request->hasFile('image')) {
                // Eliminar la imagen anterior si existe
                if ($user->image) {
                    Storage::disk('public')->delete('perfiles/' . $user->image);
                }
        
                // Almacenar la nueva imagen
                $imagePath = $request->file('image')->store('perfiles', 'public');
                $imageName = basename($imagePath);
                $user->image = $imageName;
            }

            $user->save();

            return response()->json([
                'message' => 'Usuario actualizado satisfactoriamente',
                'user' => new UserResource($user)
            ], 200);
        } 
        catch(ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el usuario'], 500);
        }
    }

    public function show($username)
    {
        $user = User::where('username', '=', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        return response()->json(new UserResource($user));
    }

    //funcion para buscar usuarios por nombre, correo o username
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', "%$query%")
            ->orWhere('lastname', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('username', 'LIKE', "%$query%")
            ->get();

        return response()->json([
            'users' => UserResource::collection($users)
        ]);
    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado satisfactoriamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario'], 500);
        }
    }
}
