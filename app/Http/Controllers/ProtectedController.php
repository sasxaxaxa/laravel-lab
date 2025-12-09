<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProtectedController extends Controller
{
    /**
     * Показать защищенную страницу (только для авторизованных)
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Требуется аутентификация');
        }
        
        $user = Auth::user();
        $tokens = $user->tokens()->latest()->get();
        
        return view('pages.protected.dashboard', [
            'user' => $user,
            'tokens' => $tokens,
        ]);
    }

    /**
     * Показать страницу управления токенами
     */
    public function tokens()
    {
        $user = Auth::user();
        $tokens = $user->tokens()->latest()->get();
        
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
        $user = Auth::user();
        $tokens = $user->tokens()->latest()->get();
        
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
            'token_name' => 'required|string|min:3|max:50',
        ]);

        $token = $request->user()->createToken(
            $request->token_name,
            ['*']
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Токен успешно создан',
                'token' => $token->plainTextToken,
                'token_id' => $token->accessToken->id,
            ]);
        }

        return redirect()->route('protected.tokens')
            ->with('sanctum_token', $token->plainTextToken)
            ->with('success', 'Токен успешно создан! Сохраните его.')
            ->with('token_id', $token->accessToken->id);
    }

    /**
     * Удалить токен
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $deleted = $request->user()->tokens()->where('id', $tokenId)->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => $deleted ? 'Токен успешно отозван' : 'Токен не найден',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->route('protected.tokens')
            ->with('success', $deleted ? 'Токен успешно отозван' : 'Токен не найден');
    }
}