@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Регистрация
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person-circle me-1"></i>Имя
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required>
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
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Подтверждение пароля
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Зарегистрироваться
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Уже есть аккаунт? Войти
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-link text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>На главную
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center bg-light py-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>Ваши данные защищены
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection