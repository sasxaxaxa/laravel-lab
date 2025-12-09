<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanctumWebAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'Пожалуйста, войдите в систему (требуется Sanctum аутентификация).');
        }
        
        if ($request->user()->tokens()->count() === 0) {
            $token = $request->user()->createToken('auto-generated')->plainTextToken;
            session(['sanctum_token' => $token]);
        }
        
        return $next($request);
    }
}