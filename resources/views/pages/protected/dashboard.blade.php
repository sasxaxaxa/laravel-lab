@extends('layouts.app')

@section('title', 'Защищенная панель')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-check me-2"></i>Защищенная панель управления
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Информация о пользователе</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Имя:</strong> {{ $user->name }}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>ID:</strong> {{ $user->id }}</p>
                                    <p><strong>Зарегистрирован:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Токены аутентификации</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Всего токенов:</strong> {{ $tokens->count() }}</p>
                                    @if(session('sanctum_token'))
                                        <p class="small">
                                            <strong>Текущий токен:</strong>
                                            <code class="d-block mt-1 p-2 bg-light rounded small">
                                                {{ substr(session('sanctum_token'), 0, 30) }}...
                                            </code>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">Управление токенами Sanctum</h5>
                        </div>
                        <div class="card-body">
                            @if($tokens->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Имя</th>
                                            <th>Права</th>
                                            <th>Последнее использование</th>
                                            <th>Создан</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tokens as $token)
                                        <tr>
                                            <td>{{ $token->id }}</td>
                                            <td>{{ $token->name }}</td>
                                            <td>
                                                @foreach($token->abilities as $ability)
                                                    <span class="badge bg-secondary me-1">{{ $ability }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $token->last_used_at ? $token->last_used_at->format('d.m.Y H:i') : 'Никогда' }}</td>
                                            <td>{{ $token->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('protected.revokeToken', $token->id) }}" 
                                                      onsubmit="return confirm('Отозвать токен?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Отозвать
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted">Нет активных токенов</p>
                            @endif

                            <form method="POST" action="{{ route('protected.createToken') }}" class="mt-4">
                                @csrf
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="token_name" 
                                           placeholder="Название токена" 
                                           required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i> Создать токен
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Информация</h6>
                        <p class="mb-0">
                            Эта страница защищена middleware <code>auth:sanctum</code>. 
                            Доступ возможен только после успешной аутентификации.
                        </p>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> На главную
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-box-arrow-right me-1"></i> Выйти
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection