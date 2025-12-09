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
              @php
                $approvedCommentsCount = $article->comments()->where(function($query) {
                    $query->where('status', 'approved')
                          ->orWhere('is_approved', true);
                })->count();
              @endphp
              <span class="badge bg-secondary">{{ $approvedCommentsCount }}</span>
            </h5>
          </div>

          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @auth
              <form method="POST" action="{{ route('comments.store', $article) }}" class="mb-4">
                @csrf
                <div class="mb-3">
                  <textarea class="form-control @error('content') is-invalid @enderror" 
                            name="content" 
                            rows="3" 
                            placeholder="Напишите ваш комментарий..."
                            required>{{ old('content') }}</textarea>
                  @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <div class="form-text">
                    <i class="bi bi-info-circle"></i> Ваш комментарий будет опубликован после проверки модератором.
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-send me-1"></i>Отправить на модерацию
                </button>
              </form>
            @else
              <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <a href="{{ route('login') }}" class="alert-link">Войдите</a> или
                <a href="{{ route('register') }}" class="alert-link">зарегистрируйтесь</a>,
                чтобы оставить комментарий.
              </div>
            @endauth

            <div class="comments-list">
              @php
                $approvedComments = $article->comments()->where(function($query) {
                    $query->where('status', 'approved')
                          ->orWhere('is_approved', true);
                })->with('user')->latest()->get();
              @endphp
              
              @forelse($approvedComments as $comment)
                <div class="comment-item border-bottom pb-3 mb-3">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <div class="fw-bold mb-1">
                        <i class="bi bi-person-circle me-1"></i>
                        @if($comment->user)
                          {{ $comment->user->name }}
                          @if($comment->user->isModerator())
                            <span class="badge bg-success ms-2">Модератор</span>
                          @endif
                        @else
                          <span class="text-muted">Удаленный пользователь</span>
                        @endif
                      </div>
                      <small class="text-muted">
                        <i class="bi bi-calendar me-1"></i>{{ $comment->created_at->format('d.m.Y H:i') }}
                      </small>
                      @if($comment->moderated_at)
                        <small class="text-muted ms-2">
                          <i class="bi bi-shield-check me-1"></i>Проверено {{ $comment->moderated_at->format('d.m.Y') }}
                        </small>
                      @endif
                    </div>
                    
                    @if($comment->isApproved())
                      <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>Одобрено
                      </span>
                    @endif
                  </div>
                  
                  <p class="mb-2 mt-3">{{ $comment->content }}</p>

                  @can('update', $comment)
                    <div class="btn-group btn-group-sm mt-2">
                      <button class="btn btn-outline-warning btn-sm"
                        onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')">
                        <i class="bi bi-pencil"></i> Редактировать
                      </button>
                      <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                        onsubmit="return confirm('Удалить комментарий?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                          <i class="bi bi-trash"></i> Удалить
                        </button>
                      </form>
                    </div>
                  @endcan
                </div>
              @empty
                <div class="text-center py-4">
                  <i class="bi bi-chat-square-text display-4 text-muted mb-3"></i>
                  <h6 class="text-muted">Пока нет комментариев</h6>
                  <p class="text-muted small">Будьте первым, кто оставит комментарий!</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        @if(auth()->check() && (auth()->user()->isModerator() || auth()->user()->role === 'admin'))
          @php
            $pendingComments = $article->comments()->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere(function($q) {
                          $q->where('is_approved', false)
                            ->where('is_rejected', false);
                      });
            })->with('user')->latest()->get();
          @endphp
          
          @if($pendingComments->count() > 0)
            <div class="card mt-4 border-warning">
              <div class="card-header bg-warning text-dark">
                <div class="d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Комментарии на модерации
                    <span class="badge bg-danger">{{ $pendingComments->count() }}</span>
                  </h6>
                  <a href="{{ route('comments.pending') }}" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-arrow-right"></i> В панель модерации
                  </a>
                </div>
              </div>
              <div class="card-body">
                @foreach($pendingComments as $comment)
                  <div class="border-start border-warning ps-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                      <div>
                        <strong>
                          @if($comment->user)
                            {{ $comment->user->name }}
                          @else
                            Аноним
                          @endif
                        </strong>
                        <small class="text-muted ms-2">
                          <i class="bi bi-calendar"></i> {{ $comment->created_at->format('d.m.Y H:i') }}
                        </small>
                      </div>
                    </div>
                    <p class="mb-3">{{ $comment->content }}</p>
                    <div class="btn-group btn-group-sm">
                      <form method="POST" action="{{ route('comments.approve', $comment) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                          <i class="bi bi-check-circle me-1"></i>Одобрить
                        </button>
                      </form>
                      <button type="button" class="btn btn-danger btn-sm" 
                              data-bs-toggle="modal" 
                              data-bs-target="#rejectModal{{ $comment->id }}">
                        <i class="bi bi-x-circle me-1"></i>Отклонить
                      </button>
                      <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline"
                        onsubmit="return confirm('Удалить комментарий?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>

                    <div class="modal fade" id="rejectModal{{ $comment->id }}" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Отклонить комментарий</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form method="POST" action="{{ route('comments.reject', $comment) }}">
                            @csrf
                            <div class="modal-body">
                              <p>Вы уверены, что хотите отклонить этот комментарий?</p>
                              <div class="mb-3">
                                <label class="form-label">Причина отклонения (необязательно)</label>
                                <textarea class="form-control" 
                                          name="rejection_reason" 
                                          rows="3"
                                          placeholder="Укажите причину отклонения..."></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                              <button type="submit" class="btn btn-danger">Отклонить</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
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
              <li class="mt-3">
                <strong>Комментарии:</strong>
                <span class="text-muted ms-2">
                  {{ $approvedCommentsCount }} одобрено
                  @if(auth()->check() && (auth()->user()->isModerator() || auth()->user()->role === 'admin'))
                    / {{ $pendingComments->count() ?? 0 }} на модерации
                  @endif
                </span>
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
                      <i class="bi bi-chat ms-2"></i> {{ $related->comments()->approved()->count() }}
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

  @push('scripts')
  <script>
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
  @endpush

  <style>
    .article-content {
      line-height: 1.8;
      font-size: 1.1rem;
    }

    .article-content p {
      margin-bottom: 1.5rem;
    }
    
    .comment-item {
      transition: background-color 0.2s;
    }
    
    .comment-item:hover {
      background-color: rgba(0,0,0,0.02);
    }
  </style>
@endsection