@extends('layouts.app')

@section('title', 'Мои уведомления')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-bell me-2"></i>Мои уведомления
                    </h5>
                    <div>
                        @if($notifications->count() > 0)
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline me-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-all me-1"></i>Отметить все как прочитанные
                                </button>
                            </form>
                            <form action="{{ route('notifications.destroyAll') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Удалить все уведомления? Это действие нельзя отменить.')">
                                    <i class="bi bi-trash me-1"></i>Удалить все
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action p-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex align-items-start mb-2">
                                                @if(!$notification->read_at)
                                                    <span class="notification-dot me-2 mt-1"></span>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1 {{ $notification->read_at ? '' : 'fw-bold' }}">
                                                        {{ $notification->data['article_title'] ?? 'Новая статья' }}
                                                    </h6>
                                                    <p class="mb-1 text-muted">
                                                        {{ $notification->data['message'] ?? 'Новое уведомление' }}
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person me-1"></i>
                                                        Автор: {{ $notification->data['author_name'] ?? 'Неизвестен' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block mb-2">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('notifications.read', $notification->id) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>Перейти
                                                </a>
                                                <form action="{{ route('notifications.destroy', $notification->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Удалить это уведомление?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">У вас пока нет уведомлений</h5>
                            <p class="text-muted">Когда у вас появятся новые уведомления, они будут отображаться здесь</p>
                            <a href="{{ route('articles.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-newspaper me-1"></i>Перейти к статьям
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection