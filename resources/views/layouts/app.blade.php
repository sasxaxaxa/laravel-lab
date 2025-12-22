<!DOCTYPE html>
<html lang="ru">

<head>
    @vite(entrypoints: ['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Мой сайт')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .navbar-brand {
            font-weight: 600;
        }

        footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        .user-dropdown-toggle::after {
            display: none;
        }

        /* Стили для уведомлений */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
        
        .notification-dropdown {
            min-width: 300px;
        }
        
        .notification-item {
            white-space: normal;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-time {
            font-size: 0.8rem;
        }
        
        .notification-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #dc3545;
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div id="app"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="bi bi-house-door me-2"></i>News
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts') }}">Контакты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articles.index') }}">
                            <i class="bi bi-newspaper me-1"></i>Новости
                        </a>
                    </li>
                    @auth
                        @can('create-article')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('articles.create') }}">
                                    <i class="bi bi-plus-circle me-1"></i>Создать новость
                                </a>
                            </li>
                        @endcan

                        @can('manage-comments')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('comments.pending') }}">
                                    <i class="bi bi-chat-dots me-1"></i>Модерация
                                    @php
                                        $pendingCount = \App\Models\Comment::pending()->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-danger">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endcan

                        @can('is-moderator')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-shield-check me-1"></i>Панель модератора
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('protected.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Дашборд
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('articles.create') }}">
                                            <i class="bi bi-file-earmark-plus me-2"></i>Новая статья
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('comments.pending') }}">
                                            <i class="bi bi-chat-square-text me-2"></i>Модерация комментариев
                                            @if($pendingCount > 0)
                                                <span class="badge bg-danger float-end">{{ $pendingCount }}</span>
                                            @endif
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">
                                            <i class="bi bi-people me-2"></i>Пользователи
                                        </a></li>
                                </ul>
                            </li>
                        @endcan
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- Уведомления -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                @php
                                    $unreadCount = auth()->user()->unreadNotifications->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger notification-badge">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                                <li class="dropdown-header px-3 pt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Уведомления</strong>
                                        @if($unreadCount > 0)
                                            <a href="{{ route('notifications.markAllAsRead') }}" 
                                               class="btn btn-sm btn-outline-success"
                                               onclick="return confirm('Отметить все как прочитанные?')">
                                                Отметить все
                                            </a>
                                        @endif
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                
                                @if($unreadCount > 0)
                                    @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                                        <li>
                                            <a class="dropdown-item notification-item p-3" 
                                               href="{{ route('notifications.read', $notification->id) }}">
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <span class="notification-dot"></span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold mb-1">
                                                            {{ $notification->data['article_title'] ?? 'Новая статья' }}
                                                        </div>
                                                        <div class="text-muted small mb-1">
                                                            {{ $notification->data['message'] ?? 'Новое уведомление' }}
                                                        </div>
                                                        <div class="text-muted notification-time">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        @if(!$loop->last)
                                            <li><hr class="dropdown-divider m-0"></li>
                                        @endif
                                    @endforeach
                                @else
                                    <li class="px-3 py-3 text-center text-muted">
                                        <i class="bi bi-bell-slash fs-4 mb-2 d-block"></i>
                                        Нет новых уведомлений
                                    </li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <div class="d-grid px-3 pb-2">
                                        <a href="{{ route('notifications.index') }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-list me-1"></i>Все уведомления
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <!-- Профиль пользователя -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->name }}
                                @if(Auth::user()->hasRole('moderator'))
                                    <span class="badge bg-warning ms-1">Модератор</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('protected.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Панель управления
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('protected.tokens') }}">
                                        <i class="bi bi-key me-2"></i>API Токены
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="bi bi-bell me-2"></i>Уведомления
                                        @if($unreadCount > 0)
                                            <span class="badge bg-danger float-end">{{ $unreadCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Выйти
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Войти
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Регистрация
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <main class="container my-5">
        @yield('content')
    </main>

    <footer class="py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                <strong>Симаева Александра Вячеславовна</strong> | Группа: <span class="text-primary">241-321</span>
            </p>
            <small class="text-muted">© {{ date('Y') }} Учебный проект</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('Вы действительно хотите выйти?')) {
                        e.preventDefault();
                    }
                });
            }
            
            // Автообновление счетчика уведомлений (опционально)
            function updateNotificationCount() {
                fetch('{{ route("notifications.unreadCount") }}')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.notification-badge');
                        if (data.count > 0) {
                            if (!badge) {
                                // Создать бейдж если его нет
                                const bellIcon = document.querySelector('.nav-link[data-bs-toggle="dropdown"] i.bi-bell');
                                if (bellIcon) {
                                    const newBadge = document.createElement('span');
                                    newBadge.className = 'badge bg-danger notification-badge';
                                    newBadge.textContent = data.count > 9 ? '9+' : data.count;
                                    bellIcon.parentNode.appendChild(newBadge);
                                }
                            } else {
                                badge.textContent = data.count > 9 ? '9+' : data.count;
                            }
                        } else if (badge) {
                            badge.remove();
                        }
                    })
                    .catch(error => console.error('Error updating notification count:', error));
            }
            
            // Обновлять каждые 30 секунд
            setInterval(updateNotificationCount, 30000);
        });
    </script>
</body>
</html>