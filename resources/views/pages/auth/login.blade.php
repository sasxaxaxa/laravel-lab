@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Вход в систему
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>Пароль
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="remember" 
                                       name="remember">
                                <label class="form-check-label" for="remember">
                                    Запомнить меня
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Войти
                            </button>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>Нет аккаунта? Зарегистрироваться
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-link text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>На главную
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center bg-light py-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-lock me-1"></i>Защищено Sanctum
                    </small>
                </div>
            </div>

            @if(Auth::check() && session('sanctum_token'))
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-key me-2"></i>Токен аутентификации
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2">
                        <strong>Токен Sanctum:</strong> 
                        <code class="d-block mt-1 p-2 bg-light rounded">{{ substr(session('sanctum_token'), 0, 30) }}...</code>
                    </p>
                    <p class="small text-muted mb-0">
                        Этот токен используется для API аутентификации через Sanctum
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection