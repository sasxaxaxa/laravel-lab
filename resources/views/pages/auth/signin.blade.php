@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-center">
                        <i class="bi bi-person-plus me-2"></i>Регистрация
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ url('/register') }}" id="registrationForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="bi bi-person-circle me-1"></i>Имя пользователя
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Введите ваше имя">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Минимум 3 символа</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email адрес
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="example@mail.ru">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Мы никогда не передадим вашу почту третьим лицам</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>Пароль
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Придумайте надежный пароль">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Минимум 6 символов</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Подтверждение пароля
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   placeholder="Повторите пароль">
                        </div>
                        
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Зарегистрироваться
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>На главную
                            </a>
                        </div>
                    </form>
                    
                    <div class="mt-4" id="responseData" style="display: none;">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-code-slash me-2"></i>Данные формы (JSON ответ)
                            </h6>
                            <pre class="mb-0" id="jsonOutput"></pre>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light py-3">
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>Защищено CSRF токеном
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="card border-info mt-4">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-info-circle me-2"></i>Как это работает?
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-2"></i>
                            <strong>CSRF защита:</strong> Токен автоматически добавляется в форму
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-2"></i>
                            <strong>Валидация:</strong> Проверка данных на стороне сервера
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-2"></i>
                            <strong>JSON ответ:</strong> После отправки получим данные в формате JSON
                        </li>
                        <li>
                            <i class="bi bi-check text-success me-2"></i>
                            <strong>Безопасность:</strong> Пароли хешируются
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const responseDiv = document.getElementById('responseData');
    const jsonOutput = document.getElementById('jsonOutput');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Отправка...';
        submitBtn.disabled = true;
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            jsonOutput.textContent = JSON.stringify(data, null, 2);
            responseDiv.style.display = 'block';
            
            responseDiv.scrollIntoView({ behavior: 'smooth' });
            
            if (data.success) {
                form.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            jsonOutput.textContent = JSON.stringify({error: 'Ошибка отправки'}, null, 2);
            responseDiv.style.display = 'block';
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>

<style>
    .card {
        border-radius: 15px;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@endsection