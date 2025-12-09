{{-- resources/views/pages/comments/pending.blade.php --}}
@extends('layouts.app')

@section('title', 'Ожидающие комментарии')

@section('content')
  <style>
    .btn-group-sm>form {
      margin: 0;
      padding: 0;
    }

    .btn-group-sm form button {
      border-radius: 0;
      margin: 0;
    }

    .btn-group-sm>form:first-child button {
      border-top-left-radius: 0.25rem !important;
      border-bottom-left-radius: 0.25rem !important;
    }

    .btn-group-sm>form:last-child button,
    .btn-group-sm>button:last-child {
      border-top-right-radius: 0.25rem !important;
      border-bottom-right-radius: 0.25rem !important;
    }
  </style>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card shadow-lg">
          <div class="card-header bg-warning text-dark">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0">
                <i class="bi bi-chat-dots me-2"></i>Ожидающие модерации комментарии
              </h4>
              <div>
                <span class="badge bg-dark fs-6">{{ $comments->total() }}</span>
              </div>
            </div>
          </div>

          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if($comments->isEmpty())
              <div class="text-center py-5">
                <i class="bi bi-check-circle text-success display-4 mb-3"></i>
                <h5 class="text-muted">Нет комментариев, ожидающих модерации</h5>
                <p class="text-muted">Все комментарии проверены и одобрены.</p>
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th width="50">ID</th>
                      <th>Комментарий</th>
                      <th>Статья</th>
                      <th>Автор</th>
                      <th>Дата</th>
                      <th width="150">Действия</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($comments as $comment)
                      <tr>
                        <td class="text-muted">#{{ $comment->id }}</td>
                        <td>
                          <div class="fw-semibold">{{ Str::limit($comment->content, 100) }}</div>
                          @if($comment->user)
                            <small class="text-muted">
                              <i class="bi bi-person me-1"></i>
                              {{ $comment->user->name }}
                            </small>
                          @endif
                        </td>
                        <td>
                          @if($comment->article)
                            <a href="{{ route('articles.show', $comment->article) }}" class="text-decoration-none">
                              {{ Str::limit($comment->article->title, 50) }}
                            </a>
                          @else
                            <span class="text-muted">Статья удалена</span>
                          @endif
                        </td>
                        <td>
                          @if($comment->user)
                            <div class="d-flex align-items-center">
                              <div class="me-2">
                                <i class="bi bi-person-circle"></i>
                              </div>
                              <div>
                                <div class="fw-semibold">{{ $comment->user->name }}</div>
                                <small class="text-muted">{{ $comment->user->email }}</small>
                              </div>
                            </div>
                          @else
                            <span class="text-muted">Аноним</span>
                          @endif
                        </td>
                        <td>
                          <small class="text-muted">
                            {{ $comment->created_at->format('d.m.Y') }}<br>
                            {{ $comment->created_at->format('H:i') }}
                          </small>
                        </td>
                        <td>
                          <div class="btn-group btn-group-sm d-flex" role="group">
                            <form method="POST" action="{{ route('comments.approve', $comment) }}" class="d-inline">
                              @csrf
                              <button type="submit" class="btn btn-success rounded-0" title="Одобрить комментарий">
                                <i class="bi bi-check-lg"></i>
                              </button>
                            </form>

                            <button type="button" class="btn btn-info rounded-0" data-bs-toggle="modal"
                              data-bs-target="#editModal{{ $comment->id }}" title="Редактировать">
                              <i class="bi bi-pencil"></i>
                            </button>

                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline"
                              onsubmit="return confirm('Удалить комментарий?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger rounded-0" title="Удалить">
                                <i class="bi bi-trash"></i>
                              </button>
                            </form>
                          </div>
                          <div class="modal fade" id="editModal{{ $comment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Редактирование комментария</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('comments.update', $comment) }}">
                                  @csrf
                                  @method('PUT')
                                  <div class="modal-body">
                                    <div class="mb-3">
                                      <label for="content{{ $comment->id }}" class="form-label">Комментарий</label>
                                      <textarea class="form-control" id="content{{ $comment->id }}" name="content" rows="4"
                                        required>{{ $comment->content }}</textarea>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              @if($comments->hasPages())
                <div class="row mt-5">
                  <div class="col-12">
                    <nav aria-label="Page navigation">
                      <ul class="pagination justify-content-center">
                        @if ($comments->onFirstPage())
                          <li class="page-item disabled">
                            <span class="page-link">
                              <i class="bi bi-chevron-left"></i> Назад
                            </span>
                          </li>
                        @else
                          <li class="page-item">
                            <a class="page-link" href="{{ $comments->previousPageUrl() }}">
                              <i class="bi bi-chevron-left"></i> Назад
                            </a>
                          </li>
                        @endif

                        @php
                          $current = $comments->currentPage();
                          $last = $comments->lastPage();
                          $start = max(1, $current - 2);
                          $end = min($last, $current + 2);
                        @endphp

                        @if ($start > 1)
                          <li class="page-item">
                            <a class="page-link" href="{{ $comments->url(1) }}">1</a>
                          </li>
                          @if ($start > 2)
                            <li class="page-item disabled">
                              <span class="page-link">...</span>
                            </li>
                          @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                          <li class="page-item {{ $i == $current ? 'active' : '' }}">
                            @if ($i == $current)
                              <span class="page-link">{{ $i }}</span>
                            @else
                              <a class="page-link" href="{{ $comments->url($i) }}">{{ $i }}</a>
                            @endif
                          </li>
                        @endfor

                        @if ($end < $last)
                          @if ($end < $last - 1)
                            <li class="page-item disabled">
                              <span class="page-link">...</span>
                            </li>
                          @endif
                          <li class="page-item">
                            <a class="page-link" href="{{ $comments->url($last) }}">{{ $last }}</a>
                          </li>
                        @endif

                        @if ($comments->hasMorePages())
                          <li class="page-item">
                            <a class="page-link" href="{{ $comments->nextPageUrl() }}">
                              Вперед <i class="bi bi-chevron-right"></i>
                            </a>
                          </li>
                        @else
                          <li class="page-item disabled">
                            <span class="page-link">
                              Вперед <i class="bi bi-chevron-right"></i>
                            </span>
                          </li>
                        @endif
                      </ul>
                    </nav>

                    <div class="text-center mt-3">
                      <p class="text-muted mb-0">
                        Показано
                        <strong>{{ ($comments->currentPage() - 1) * $comments->perPage() + 1 }}</strong>
                        -
                        <strong>{{ min($comments->currentPage() * $comments->perPage(), $comments->total()) }}</strong>
                        из
                        <strong>{{ $comments->total() }}</strong>
                        комментариев
                      </p>
                    </div>
                  </div>
                </div>
              @endif
            @endif

            <div class="row mt-5">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Статистика комментариев</h5>
                    <div class="row text-center">
                      <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                          <h3 class="text-primary">{{ App\Models\Comment::count() }}</h3>
                          <p class="mb-0">Всего комментариев</p>
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <div class="p-3 bg-warning rounded">
                          <h3 class="text-dark">
                            {{ App\Models\Comment::where('approved', false)->orWhere('is_approved', false)->count() }}
                          </h3>
                          <p class="mb-0">Ожидают модерации</p>
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <div class="p-3 bg-success rounded">
                          <h3 class="text-white">
                            {{ App\Models\Comment::where('approved', true)->orWhere('is_approved', true)->count() }}
                          </h3>
                          <p class="mb-0">Одобрено</p>
                        </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <div class="p-3 bg-info rounded">
                          @php
                            $activeUsers = App\Models\Comment::distinct('user_id')->count('user_id');
                          @endphp
                          <h3 class="text-white">{{ $activeUsers }}</h3>
                          <p class="mb-0">Активных авторов</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left me-1"></i> К статьям
                </a>
                @if(auth()->user()->isModerator() || auth()->user()->role === 'admin')
                  <a href="{{ route('protected.dashboard') }}" class="btn btn-outline-primary ms-2">
                    <i class="bi bi-speedometer2 me-1"></i> Панель модератора
                  </a>
                @endif
              </div>

              <div class="text-muted small">
                <i class="bi bi-info-circle me-1"></i>
                Показано: {{ $comments->count() }} из {{ $comments->total() }}
              </div>
            </div>
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

      @if($errors->any())
        @foreach($comments as $comment)
          @if(old('comment_id') == $comment->id)
            document.addEventListener('DOMContentLoaded', function () {
              const editModal = new bootstrap.Modal(document.getElementById('editModal{{ $comment->id }}'));
              editModal.show();
            });
            @break
          @endif
        @endforeach
      @endif
    </script>
  @endpush
@endsection