@extends('layouts.app')

@section('title', $article->title)

@section('content')
  <div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
        <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Новости</a></li>
        <li class="breadcrumb-item"><a
            href="{{ route('articles.category', $article->category) }}">{{ ucfirst($article->category) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
          @if($article->image)
            <div class="mb-4 rounded overflow-hidden shadow">
              <img src="{{ asset($article->image) }}" class="img-fluid w-100" alt="{{ $article->title }}"
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

        <div class="card mt-4">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="bi bi-chat-text me-2"></i>Комментарии
              <span class="badge bg-secondary">{{ $article->comments()->approved()->count() }}</span>
            </h5>
          </div>

          <div class="card-body">
            @auth
              <form method="POST" action="{{ route('comments.store', $article) }}" class="mb-4">
                @csrf
                <div class="mb-3">
                  <textarea class="form-control" name="content" rows="3" placeholder="Напишите ваш комментарий..."
                    required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-send me-1"></i>Отправить комментарий
                </button>
                @if(auth()->user()->isReader())
                  <small class="text-muted ms-2">Комментарий будет отправлен на модерацию</small>
                @endif
              </form>
            @else
              <div class="alert alert-info">
                <a href="{{ route('login') }}" class="alert-link">Войдите</a> или
                <a href="{{ route('register') }}" class="alert-link">зарегистрируйтесь</a>,
                чтобы оставить комментарий.
              </div>
            @endauth

            <div class="comments-list">
              @forelse($article->comments()->approved()->latest()->get() as $comment)
                <div class="comment-item border-bottom pb-3 mb-3">
                  <div class="d-flex justify-content-between">
                    <div class="fw-bold">
                      <i class="bi bi-person-circle me-1"></i>{{ $comment->user->name }}
                      @if($comment->user->isModerator())
                        <span class="badge bg-success ms-2">Модератор</span>
                      @endif
                    </div>
                    <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                  </div>
                  <p class="mb-2 mt-2">{{ $comment->content }}</p>

                  @can('update', $comment)
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-outline-warning btn-sm"
                        onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                        onsubmit="return confirm('Удалить комментарий?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  @endcan
                </div>
              @empty
                <p class="text-muted">Пока нет комментариев. Будьте первым!</p>
              @endforelse
            </div>
          </div>
        </div>

        @if(Gate::allows('manage-comments') && $article->comments()->pending()->count() > 0)
          <div class="card mt-4 border-warning">
            <div class="card-header bg-warning">
              <h6 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Комментарии на модерации
                <span class="badge bg-danger">{{ $article->comments()->pending()->count() }}</span>
              </h6>
            </div>
            <div class="card-body">
              @foreach($article->comments()->pending()->latest()->get() as $comment)
                <div class="border-start border-warning ps-3 mb-3">
                  <div class="d-flex justify-content-between">
                    <strong>{{ $comment->user->name }}</strong>
                    <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                  </div>
                  <p class="mb-2">{{ $comment->content }}</p>
                  <form method="POST" action="{{ route('comments.approve', $comment) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                      <i class="bi bi-check-circle me-1"></i>Одобрить
                    </button>
                  </form>
                  <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline"
                    onsubmit="return confirm('Удалить комментарий?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="bi bi-x-circle me-1"></i>Удалить
                    </button>
                  </form>
                </div>
              @endforeach
            </div>
          </div>
        @endif
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