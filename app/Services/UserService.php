<?php

namespace App\Services;

use App\Http\Resources\SuggestedUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function getSuggestedUsers(Request $request): ResourceCollection
    {
        $authUser = $request->user(); // ✅ Usuario autenticado

        // Obtener los IDs de los usuarios que sigue
        $followingIds = $authUser->followings()->select('users.id')->pluck('id');

        // Obtener los IDs de los usuarios seguidos por los que sigue el autenticado
        $secondLevelIds = User::whereIn('id', $followingIds)
            ->with('followings')
            ->get()
            ->pluck('followings')
            ->flatten()
            ->pluck('id')
            ->unique();

        // Filtrar para excluir a los que ya sigue el usuario autenticado
        $suggestedUsers = User::whereIn('id', $secondLevelIds)
            ->whereNotIn('id', $followingIds)
            ->where('id', '!=', $authUser->id)
            ->get();

        return SuggestedUserResource::collection($suggestedUsers);
    }
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser(array $data, $id)
    {
        try {
            $user = User::findOrFail($id);
            // Actualizar solo los campos que vienen en la solicitud
            $user->fill($data);
            // Encriptar la contraseña si se ha enviado
            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            // Manejo de imagen
            if (isset($data['image'])) {
                if ($user->image) {
                    Storage::disk('public')->delete('perfiles/' . $user->image);
                }

                $imagePath = $data['image']->store('perfiles', 'public');
                $user->image = basename($imagePath);
            }
            $user->save();
            return $user;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el usuario'], 500);
        }
    }

     public function searchUsers(string $query): ResourceCollection
    {
        $users = User::where('name', 'LIKE', "%$query%")
            ->orWhere('lastname', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('username', 'LIKE', "%$query%")
            ->get();

        return UserResource::collection($users);
    }

    public function deleteUser($id)
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
