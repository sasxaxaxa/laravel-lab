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
        
        return view('pages/protected/dashboard', [
            'user' => $user,
            'tokens' => $tokens,
        ]);
    }

    /**
     * Показать страницу управления токенами
     */
    public function tokens()
    {
        $user = auth()->user();
        $tokens = $user->tokens()->get();
        
        return view('pages.protected.tokens', [
            'user' => $user,
            'tokens' => $tokens,
        ]);
    }

    /**
     * API: Получить токены (JSON)
     */
    public function getTokens()
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

        // Если это AJAX запрос, вернем JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Токен успешно создан',
                'token' => $token->plainTextToken,
            ]);
        }

        // Если обычный POST запрос из формы, вернемся обратно с токеном в сессии
        return redirect()->route('protected.tokens')
            ->with('sanctum_token', $token->plainTextToken)
            ->with('success', 'Токен успешно создан! Сохраните его - он больше не будет показан.');
    }

    /**
     * Удалить токен
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        // Если это AJAX запрос, вернем JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Токен успешно отозван',
            ]);
        }

        // Если обычный запрос из формы, вернемся обратно
        return redirect()->route('protected.tokens')
            ->with('success', 'Токен успешно отозван');
    }
}