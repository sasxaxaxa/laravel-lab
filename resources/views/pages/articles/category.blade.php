@extends('layouts.app')

@section('title', 'Новости - ' . ucfirst($category))

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 text-primary">Категория: {{ ucfirst($category) }}</h1>
            <p class="lead">Новости в категории "{{ ucfirst($category) }}"</p>
        </div>
    </div>

    <!-- Статьи -->
    <div class="row">
        @foreach($articles as $article)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
    <!-- Изображение -->
          <div class="card-img-top position-relative" style="height: 200px; overflow: hidden;">
              @if($article->image)
                  <img src="{{ asset($article->image) }}" 
                      class="w-100 h-100"
                      alt="{{ $article->title }}"
                      style="object-fit: cover;">
                  
                  <!-- Затемнение для текста -->
                  <div class="position-absolute top-0 start-0 w-100 h-100" 
                      style="background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.3));">
                  </div>
                  
                  <!-- Категория поверх изображения -->
                  <div class="position-absolute bottom-0 start-0 m-3">
                      <span class="badge bg-primary">
                          {{ $article->category }}
                      </span>
                  </div>
              @else
                  <!-- Заглушка если нет изображения -->
                  <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                      <div class="text-center text-muted">
                          <i class="bi bi-image display-4"></i>
                          <p class="mt-2 small">Нет изображения</p>
                      </div>
                  </div>
              @endif
          </div>
          
          <!-- Контент статьи -->
          <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $article->title }}</h5>
              
              <p class="card-text text-muted small flex-grow-1">
                  {{ Str::limit(strip_tags($article->content), 100) }}
              </p>
              
              <div class="d-flex justify-content-between align-items-center mt-3">
                  <small class="text-muted">
                      <i class="bi bi-person"></i> {{ $article->author }}
                  </small>
                  <small class="text-muted">
                      <i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y') }}
                  </small>
              </div>
              
              <div class="d-flex justify-content-between align-items-center mt-2">
                  <small class="text-muted">
                      <i class="bi bi-eye"></i> {{ $article->views }}
                  </small>
                  <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">
                      Читать
                  </a>
              </div>
          </div>
      </div>
        </div>
        @endforeach
    </div>
    
    <!-- Пагинация -->
    @if($articles->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{-- Previous Page Link --}}
                    @if ($articles->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-left"></i> Назад
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $articles->previousPageUrl() }}">
                                <i class="bi bi-chevron-left"></i> Назад
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $current = $articles->currentPage();
                        $last = $articles->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    {{-- First Page Link --}}
                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $articles->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    {{-- Page Number Links --}}
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i == $current ? 'active' : '' }}">
                            @if ($i == $current)
                                <span class="page-link">{{ $i }}</span>
                            @else
                                <a class="page-link" href="{{ $articles->url($i) }}">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    {{-- Last Page Link --}}
                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $articles->url($last) }}">{{ $last }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($articles->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $articles->nextPageUrl() }}">
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

            {{-- Информация о страницах --}}
            <div class="text-center mt-3">
                <p class="text-muted mb-0">
                    Показано 
                    <strong>{{ ($articles->currentPage() - 1) * $articles->perPage() + 1 }}</strong> 
                    - 
                    <strong>{{ min($articles->currentPage() * $articles->perPage(), $articles->total()) }}</strong> 
                    из 
                    <strong>{{ $articles->total() }}</strong> 
                    статей
                </p>
            </div>
        </div>
    </div>
    @endif
    <!-- Информация о категории -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Статистика категории</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-primary">{{ $articles->total() }}</h3>
                                <p class="mb-0">Статей в категории</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-success">{{ App\Models\Article::published()->category($category)->count() }}</h3>
                                <p class="mb-0">Опубликовано</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-info">{{ App\Models\Article::category($category)->sum('views') }}</h3>
                                <p class="mb-0">Всего просмотров</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection