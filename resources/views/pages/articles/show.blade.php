@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Новости</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles.category', $article->category) }}">{{ ucfirst($article->category) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                @if($article->image)
<div class="mb-4 rounded overflow-hidden shadow">
    <img src="{{ asset($article->image) }}" 
         class="img-fluid w-100"
         alt="{{ $article->title }}"
         style="max-height: 400px; object-fit: cover;">
</div>
@endif
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $article->category }}</span>
                        <span class="badge bg-secondary ms-2">
                            <i class="bi bi-eye"></i> {{ $article->views }} просмотров
                        </span>
                        <span class="text-muted ms-3">
                            <i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y H:i') }}
                        </span>
                    </div>
                    
                    <h1 class="card-title">{{ $article->title }}</h1>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $article->author }}</h6>
                            <small class="text-muted">Автор статьи</small>
                        </div>
                    </div>

                    <div class="article-content">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Обновлено: {{ $article->updated_at->format('d.m.Y H:i') }}
                        </small>
                        @if(!$article->is_published)
                        <span class="badge bg-warning ms-3">Не опубликовано</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Информация</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Категория:</strong>
                            <span class="badge bg-primary ms-2">{{ $article->category }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Просмотры:</strong>
                            <span class="text-muted ms-2">{{ $article->views }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Дата публикации:</strong>
                            <span class="text-muted ms-2">{{ $article->created_at->format('d.m.Y') }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Статус:</strong>
                            @if($article->is_published)
                            <span class="badge bg-success ms-2">Опубликовано</span>
                            @else
                            <span class="badge bg-warning ms-2">Черновик</span>
                            @endif
                        </li>
                        <li>
                            <strong>ID:</strong>
                            <span class="text-muted ms-2">#{{ $article->id }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Похожие статьи</h5>
                </div>
                <div class="card-body">
                    @php
                        $relatedArticles = App\Models\Article::published()
                            ->where('category', $article->category)
                            ->where('id', '!=', $article->id)
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($relatedArticles->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($relatedArticles as $related)
                        <a href="{{ route('articles.show', $related) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ Str::limit($related->title, 40) }}</h6>
                                <small class="text-muted">{{ $related->created_at->format('d.m') }}</small>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-eye"></i> {{ $related->views }}
                            </small>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">Нет похожих статей</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.article-content {
    line-height: 1.8;
    font-size: 1.1rem;
}
.article-content p {
    margin-bottom: 1.5rem;
}
</style>
@endsection