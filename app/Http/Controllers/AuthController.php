<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function create()
    {
        return view('pages.auth.signin');
    }

    /**
     * Обработать регистрацию пользователя
     */
    public function registration(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50|regex:/^[a-zA-Zа-яА-ЯёЁ\s]+$/u',
            'email' => 'required|email|max:100',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.min' => 'Имя должно содержать минимум 3 символа.',
            'name.max' => 'Имя должно содержать не более 50 символов.',
            'name.regex' => 'Имя может содержать только буквы и пробелы.',
            
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Введите корректный email адрес.',
            'email.max' => 'Email должен содержать не более 100 символов.',
            
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум 6 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        // Если валидация не прошла
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибки валидации',
                'errors' => $validator->errors(),
                'input_data' => $request->except('password', 'password_confirmation')
            ], 422);
        }

        // Симуляция создания пользователя (в реальном проекте здесь была бы запись в БД)
        $userData = [
            'id' => rand(1000, 9999),
            'name' => $request->name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'registered_at' => now()->format('d.m.Y H:i:s'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ];

        // Успешный ответ
        return response()->json([
            'success' => true,
            'message' => 'Регистрация прошла успешно!',
            'user' => $userData,
            'validation_rules' => [
                'name' => 'required|min:3|max:50|regex:/^[a-zA-Zа-яА-ЯёЁ\s]+$/u',
                'email' => 'required|email|max:100',
                'password' => 'required|min:6|confirmed'
            ],
            'csrf_token' => $request->session()->token(),
            'timestamp' => now()->toISOString()
        ], 200);
    }
}