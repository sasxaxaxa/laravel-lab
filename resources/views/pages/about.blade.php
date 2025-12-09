@extends('layouts.app')

@section('title', 'О нас - Новостной портал')

@section('content')
<div class="container py-4">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary mb-3">
            <i class="bi bi-info-square me-2"></i>О нашем новостном портале
        </h1>
        <p class="lead text-muted">Свежие новости, проверенная информация, независимая журналистика</p>
    </div>    
    

    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h3 class="mb-0"><i class="bi bi-people-fill me-2"></i>Наша редакция</h3>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="avatar-placeholder bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-white fs-1"></i>
                        </div>
                        <h5 class="mb-1">Анна Анна</h5>
                        <p class="text-muted small mb-2">Главный редактор</p>
                        <p class="small">15 лет в журналистике, специалист по политическим вопросам</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="avatar-placeholder bg-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-white fs-1"></i>
                        </div>
                        <h5 class="mb-1">Анна Анна</h5>
                        <p class="text-muted small mb-2">Шеф-редактор</p>
                        <p class="small">Эксперт по экономическим вопросам, автор 200+ аналитических статей</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="avatar-placeholder bg-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-white fs-1"></i>
                        </div>
                        <h5 class="mb-1">Анна Анна</h5>
                        <p class="text-muted small mb-2">Технический редактор</p>
                        <p class="small">Специалист по цифровым технологиям и инновациям в медиа</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 h-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-trophy-fill fs-1 text-warning me-3"></i>
                        <div>
                            <h4 class="mb-1">Награды</h4>
                            <p class="text-muted small mb-0">Признание профессионального сообщества</p>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Лучший новостной портал 2023</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Премия "За объективность в СМИ"</li>
                        <li class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Награда "Инновации в медиа"</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card border-0 h-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-globe fs-1 text-primary me-3"></i>
                        <div>
                            <h4 class="mb-1">Партнеры</h4>
                            <p class="text-muted small mb-0">Сотрудничество с ведущими СМИ</p>
                        </div>
                    </div>
                    <p class="mb-0">
                        Мы сотрудничаем с международными информационными агентствами, 
                        что позволяет нашим читателям получать информацию из первых рук.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <div class="bg-light p-5 rounded">
            <h3 class="h2 mb-3">Присоединяйтесь к нашему сообществу!</h3>
            <p class="lead mb-4">
                Подписывайтесь на наши социальные сети, оставляйте комментарии, 
                участвуйте в обсуждениях и будьте в курсе всех важных событий.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Зарегистрироваться
                </a>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-newspaper me-2"></i>Читать новости
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
        transition: all 0.2s ease;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection