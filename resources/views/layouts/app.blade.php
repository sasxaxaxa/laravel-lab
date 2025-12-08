<!DOCTYPE html>
<html lang="ru">

<head>
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="bi bi-house-door me-2"></i>МойСайт
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i>Регистрация
                        </a>
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
</body>

</html>