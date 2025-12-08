<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;

class ProtectedController extends Controller
{
    /**
     * Показать защищенную страницу (только для авторизованных)
     */
    public function dashboard()
    {
        $user = auth()->user();
        $tokens = $user->tokens()->get();
        
        return view('pages.protected.dashboard', [
            'user' => $user,
            'tokens' => $tokens,
        ]);
    }

    /**
     * Показать информацию о токенах
     */
    public function tokens()
    {
        $user = auth()->user();
        $tokens = $user->tokens()->get();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'tokens' => $tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'last_used' => $token->last_used_at,
                    'created' => $token->created_at,
                ];
            }),
        ]);
    }

    /**
     * Создать новый токен
     */
    public function createToken(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|min:3',
        ]);

        $token = $request->user()->createToken($request->token_name);

        return response()->json([
            'message' => 'Токен успешно создан',
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * Удалить токен
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()->json([
            'message' => 'Токен успешно отозван',
        ]);
    }
}