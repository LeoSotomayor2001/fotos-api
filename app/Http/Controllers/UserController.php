<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        return response()->json([
            'users' => UserResource::collection(User::all())
        ]);
    }

      public function suggestedUsers(Request $request)
    {
        $suggestedUsers = $this->userService->getSuggestedUsers($request);

        return response()->json([
            'suggestedUsers' => $suggestedUsers
        ]);
    }
    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        $token = JWTAuth::fromUser($user);
        $success = true;
        return response()->json(compact('user', 'token', 'success'), 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        // Agregar archivo de imagen a los datos si está presente
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $user = $this->userService->updateUser($data, $id);
        if ($user instanceof JsonResponse) {
            return $user; // Retornar error si el servicio lo generó
        }

        return response()->json([
            'message' => 'Usuario actualizado satisfactoriamente',
            'user' => new UserResource($user)
        ], 200);
    }

    public function show($username)
    {
        $user = User::where('username', '=', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        return response()->json(new UserResource($user));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = $this->userService->searchUsers($query);

        return response()->json([
            'users' => $users
        ]);
    }

    public function destroy($id)
    {
        return $this->userService->deleteUser($id);
    }
}
