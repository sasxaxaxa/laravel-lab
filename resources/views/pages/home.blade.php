@extends('layouts.app')

@section('title', 'Новостной сайт - Главная')

@section('content')
<div class="container">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary mb-3">Новостной портал</h1>
        <p class="lead text-muted">Актуальные статьи и публикации</p>
        <div class="alert alert-info d-inline-block">
            <i class="bi bi-database me-2"></i>Загружено статей: {{ count($articles) }}
        </div>
    </div>

    <div class="row g-4">
        @foreach($articles as $index => $article)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="position-relative">
                    <a href="{{ route('gallery', $index) }}" class="d-block">
                        <img src="/{{ $article['preview_image'] }}" 
                             class="card-img-top" 
                             alt="{{ $article['name'] }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary">
                                #{{ $index + 1 }}
                            </span>
                        </div>
                    </a>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>{{ $article['date'] }}
                        </small>
                    </div>
                    
                    <h5 class="card-title">{{ Str::limit($article['name'], 50) }}</h5>
                    
                    @if(isset($article['shortDesc']))
                    <p class="card-text text-muted small flex-grow-1">
                        {{ $article['shortDesc'] }}
                    </p>
                    @else
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($article['desc'], 100) }}
                    </p>
                    @endif
                    
                    <div class="mt-auto pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('gallery', $index) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-image me-1"></i>Просмотр
                            </a>
                            <small class="text-muted">
                                {{ Str::limit($article['preview_image'], 15) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Таблица с информацией -->
    <div class="mt-5">
        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>Все статьи
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Дата</th>
                                <th>Превью</th>
                                <th>Полное изображение</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $index => $article)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>{{ Str::limit($article['name'], 40) }}</td>
                                <td>{{ $article['date'] }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $article['preview_image'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $article['full_image'] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('gallery', $index) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted small">
                <i class="bi bi-info-circle me-1"></i>Данные загружены из articles.json
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card-img-top {
        transition: transform 0.5s ease;
    }
    .hover-shadow:hover .card-img-top {
        transform: scale(1.05);
    }
</style>
@endsection