@extends('layouts.app')

@section('title', 'Управление токенами')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-key me-2"></i>Управление API токенами
                    </h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('sanctum_token'))
                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle me-2"></i>Новый токен создан</h6>
                        <p>Сохраните этот токен - он будет показан только один раз!</p>
                        <code class="d-block p-3 bg-dark text-white rounded mt-2">
                            {{ session('sanctum_token') }}
                        </code>
                        <small class="text-muted mt-2 d-block">
                            Используйте этот токен для доступа к API. Храните его в безопасном месте.
                        </small>
                    </div>
                    @endif

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Создать новый токен</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('protected.createToken') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="token_name" class="form-label">Название токена</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="token_name" 
                                                   name="token_name" 
                                                   placeholder="Например: Мой API токен"
                                                   required>
                                            <div class="form-text">
                                                Придумайте описательное название для вашего токена.
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-plus-circle me-1"></i> Создать токен
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Информация</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Текущий пользователь:</strong> {{ $user->name }}</p>
                                    <p><strong>Всего токенов:</strong> {{ $tokens->count() }}</p>
                                    <p class="small text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Каждый токен предоставляет доступ к API. Отзывайте неиспользуемые токены.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($tokens->count() > 0)
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Мои токены</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Название</th>
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
                                                @if(in_array('*', $token->abilities))
                                                    <span class="badge bg-success">Все права</span>
                                                @else
                                                    @foreach($token->abilities as $ability)
                                                        <span class="badge bg-secondary me-1">{{ $ability }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                {{ $token->last_used_at ? $token->last_used_at->format('d.m.Y H:i') : 'Никогда' }}
                                            </td>
                                            <td>{{ $token->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('protected.revokeToken', $token->id) }}" 
                                                      onsubmit="return confirm('Отозвать токен «{{ $token->name }}»?')" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash me-1"></i> Отозвать
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        У вас еще нет созданных токенов. Создайте первый токен выше.
                    </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('protected.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Назад к панели
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house me-1"></i> На главную
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection