<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

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
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
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

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

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
            $token = $user->createToken('web-token')->plainTextToken;
            
            session(['sanctum_token' => $token]);

            return redirect()->intended(route('home'))
                ->with('success', 'Вы успешно вошли в систему!');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    /**
     * Выход пользователя
     */
    public function logout(Request $request)
    {
        if ($token = session('sanctum_token')) {
            $user = Auth::user();
            $user->tokens()->where('token', hash('sha256', explode('|', $token)[1]))->delete();
            session()->forget('sanctum_token');
        }

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Вы успешно вышли из системы.');
    }
}