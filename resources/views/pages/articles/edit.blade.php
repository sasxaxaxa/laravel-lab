@extends('layouts.app')

@section('title', 'Редактировать: ' . $article->title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Редактировать статью
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('articles.update', $article) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Заголовок -->
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Заголовок *</label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $article->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Категория -->
                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label">Категория *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category" 
                                        required>
                                    <option value="">Выберите категорию</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category', $article->category) == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL) *</label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $article->slug) }}"
                                   required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Автор -->
                        <div class="mb-3">
                            <label for="author" class="form-label">Автор *</label>
                            <input type="text" 
                                   class="form-control @error('author') is-invalid @enderror" 
                                   id="author" 
                                   name="author" 
                                   value="{{ old('author', $article->author) }}"
                                   required>
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Изображение -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение</label>
                            <select class="form-select @error('image') is-invalid @enderror" 
                                    id="image" 
                                    name="image">
                                <option value="">Без изображения</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="/images/articles/news{{ $i }}.jpg" {{ old('image', $article->image) == "/images/articles/news{$i}.jpg" ? 'selected' : '' }}>
                                        Изображение {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Содержание -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Содержание *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="10"
                                      required>{{ old('content', $article->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Статус публикации -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_published" 
                                       name="is_published" 
                                       value="1" 
                                       {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Опубликовать статью
                                </label>
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Назад
                            </a>
                            <div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-save me-1"></i>Обновить
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Форма удаления -->
                    <hr class="my-4">
                    <form method="POST" action="{{ route('articles.destroy', $article) }}" 
                          onsubmit="return confirm('Вы уверены что хотите удалить эту статью?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Удалить статью
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection