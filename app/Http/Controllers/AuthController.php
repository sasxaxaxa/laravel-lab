<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Показать форму регистрации
     */
    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }

    /**
     * Обработать регистрацию пользователя
     * Согласно заданию: redirect на форму авторизации
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Имя обязательно для заполнения',
            'name.min' => 'Имя должно быть не менее 3 символов',
            'email.required' => 'Email обязателен',
            'email.email' => 'Введите корректный email',
            'email.unique' => 'Этот email уже зарегистрирован',
            'password.required' => 'Пароль обязателен',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Пароль должен быть не менее 6 символов',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->createToken('web-registration-token')->plainTextToken;

        return redirect()->route('login')
            ->with('success', 'Регистрация прошла успешно! Теперь выполните вход.');
    }

    /**
     * Показать форму входа
     */
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    /**
     * Обработать вход пользователя
     * Согласно заданию: аутентификация с присвоением токена
     * и redirect на главную в обход посредника auth
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email обязателен',
            'email.email' => 'Введите корректный email',
            'password.required' => 'Пароль обязателен',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            $user->tokens()->delete();
            
            $token = $user->createToken('web-session-token')->plainTextToken;
            
            Session::put('sanctum_token', $token);
            Session::put('sanctum_token_name', 'web-session-token');
            
            return redirect()->route('home')
                ->with('success', 'Вы успешно вошли в систему!')
                ->with('token_info', 'Sanctum токен создан: ' . Str::limit($token, 20));
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    /**
     * Выход пользователя
     * Согласно заданию: удаление токена, аннулирование сессии,
     * обновление CSRF токена и redirect на главную
     */
    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->tokens()->delete();
            
            Session::forget('sanctum_token');
            Session::forget('sanctum_token_name');
        }
        
        Auth::logout();
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
            ->with('success', 'Вы успешно вышли из системы. Все токены удалены.');
    }
}