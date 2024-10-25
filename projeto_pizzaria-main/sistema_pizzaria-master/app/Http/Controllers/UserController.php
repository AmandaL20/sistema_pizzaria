<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 * @author Vinícius Siqueira
 * @link https://github.com/ViniciusSCS
 * @date 2024-08-23 21:48:54
 * @copyright UniEVANGÉLICA
 */
class UserController extends Controller
{
    /**
     * lista de recursos.
     */
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'created_at')->paginate(10);
        return response()->json([
            'status' => 200,
            'message' => 'Usuários encontrados!!',
            'users' => $users
        ]);
    }

    /**
     * usuário autenticado.
     */
    public function me(): JsonResponse
    {
        $user = Auth::user();
        return response()->json([
            'status' => 200,
            'message' => 'Usuário logado!',
            'user' => $user
        ]);
    }

    /**
     * Armazena um novo recurso no armazenamento.
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'Usuário cadastrado com sucesso!!',
            'user' => $user
        ]);
    }

    /**
     * recurso especificado.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Usuário não encontrado! Que triste!',
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Usuário encontrado com sucesso!!',
            'user' => $user
        ]);
    }

    /**
     * recurso especificado no armazenamento.
     */
    public function update(UserUpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Usuário não encontrado! Que triste!',
            ]);
        }
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        return response()->json([
            'status' => 200,
            'message' => 'Usuário atualizado com sucesso!!',
            'user' => $user
        ]);
    }

    /**
     * recurso especificado do armazenamento.
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Usuário não encontrado! Que triste!',
            ]);
        }
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Usuário deletado com sucesso!!'
        ]);
    }
}