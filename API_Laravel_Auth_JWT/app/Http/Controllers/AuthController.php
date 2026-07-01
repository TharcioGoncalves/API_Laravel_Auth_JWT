<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(Request $request):JsonResponse{

        $request->validate([
            "name" => "required|string|max:100",
            "password" => "required|string|min:6",
            "email" => "required|email|unique:users"
        ]);

        $user = User::create([
            "name" => $request->name,
            "password" => $request->password,
            "email" => $request->email
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            "status" => true,
            "message" => "Usuário cadastrado com sucesso",
            "Usuário" => $user,
            "Autorização" => [
                "token" => $token,
                "tipo" => "bearer"
            ]
        ],201);
    }

    public function login(Request $request){

        $request->validate([
            "email" => "required|email",
            "password" => "required|string|min:6"
        ]);

        $user = $request->only("email","password");
        $token = Auth::attempt($user);

        if(!$token){
            return response()->json([
                "status" => false,
                "message" => "Não autorizado"
            ],422);
        }

        return response()->json([
            "status" => true,
            "message" => "Usuário autenticado",
            "Usuário" => Auth::user(),
            "Autorização" => [
                "token" => $token,
                "tipo" => "bearer"
            ]
        ], 200);
    }

    public function logout(Request $request) : JsonResponse{
        Auth::logout();

        return response()->json([
            "status" => true,
            "message" => "Usuário deslogado"
        ], 200);
    }
}
