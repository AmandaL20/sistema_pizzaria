<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 * @author Vinícius Siqueira
 * @link https://github.com/ViniciusSCS
 * @date 2024-10-01 15:52:14
 * @copyright UniEVANGÉLICA
 */
class AuthController extends Controller
{
    protected $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => strtolower($request->input('email')),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;

            return [
                'status' => 200,
                'message' => "Usuário logado com sucesso",
                "usuario" => $user,
            ];
        }

        return [
            'status' => 404,
            'message' => "Usuário ou senha incorreto",
        ];
    }

    public function logout(Request $request)
    {
        $tokenId = $request->user()->token()->id;
        $this->tokenRepository->revokeAccessToken($tokenId);

        return ['status' => true, 'message' => "Usuário deslogado com sucesso!"];
    }
}

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pode ser ajustado 
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }
}