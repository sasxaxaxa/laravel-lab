@extends('layouts.app')

@section('title', 'Галерея - ' . $article['name'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Хлебные крошки -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Статья #{{ $article['id'] + 1 }}</li>
                </ol>
            </nav>

            <!-- Основная карточка -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-newspaper me-2"></i>{{ $article['name'] }}
                        </h4>
                        <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Назад
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Полное изображение -->
                    <div class="text-center mb-5">
                        <div class="image-container mb-3">
                            <img src="/{{ $article['full_image'] }}" 
                                 alt="{{ $article['name'] }}"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 400px; width: auto;">
                        </div>
                        
                        <div class="alert alert-info d-inline-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Полное изображение: <strong class="ms-2">{{ $article['full_image'] }}</strong>
                        </div>
                    </div>

                    <!-- Информация о статье -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Описание:</h5>
                                <div class="article-content p-3 bg-light rounded">
                                    <p class="mb-0">{{ $article['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="bi bi-card-checklist me-1"></i>Информация
                                    </h6>
                                    
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-3">
                                            <strong><i class="bi bi-hash me-2"></i>ID:</strong>
                                            <span class="badge bg-primary ms-2">#{{ $article['id'] + 1 }}</span>
                                        </li>
                                        <li class="mb-3">
                                            <strong><i class="bi bi-calendar me-2"></i>Дата:</strong>
                                            <br>
                                            <span class="text-muted">{{ $article['date'] }}</span>
                                        </li>
                                        <li class="mb-3">
                                            <strong><i class="bi bi-image me-2"></i>Превью:</strong>
                                            <br>
                                            <code class="small">{{ $article['preview_image'] }}</code>
                                        </li>
                                        <li>
                                            <strong><i class="bi bi-image-fill me-2"></i>Полное:</strong>
                                            <br>
                                            <code class="small">{{ $article['full_image'] }}</code>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Сравнение изображений -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-card-image me-2"></i>Превью изображение
                                    </h6>
                                </div>
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                        <img src="/{{ $article['preview_image'] }}" 
                                             alt="Preview" 
                                             class="img-fluid rounded"
                                             style="max-height: 180px;">
                                    </div>
                                    <div class="mt-3">
                                        <code class="bg-light p-2 rounded">{{ $article['preview_image'] }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-image-alt me-2"></i>Полное изображение
                                    </h6>
                                </div>
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                        <img src="/{{ $article['full_image'] }}" 
                                             alt="Full" 
                                             class="img-fluid rounded"
                                             style="max-height: 180px;">
                                    </div>
                                    <div class="mt-3">
                                        <code class="bg-light p-2 rounded">{{ $article['full_image'] }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Краткое описание если есть -->
                    @if(isset($article['shortDesc']))
                    <div class="alert alert-warning mt-4">
                        <h6 class="alert-heading">
                            <i class="bi bi-chat-quote me-2"></i>Краткое описание
                        </h6>
                        <p class="mb-0">{{ $article['shortDesc'] }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-file-earmark-text me-1"></i>Статья загружена из JSON
                        </small>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="bi bi-list-ul me-1"></i>Все статьи
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .image-container {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 15px;
        background: #f8f9fa;
        display: inline-block;
    }
    .article-content {
        line-height: 1.6;
        font-size: 1.05rem;
    }
</style>
@endsection