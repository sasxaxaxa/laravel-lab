@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <h1 class="card-title text-center mb-5 text-primary">
                    <i class="bi bi-telephone me-2"></i>Наши контакты
                </h1>

                <div class="row mb-5">
                    @foreach($contacts as $contact)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi {{ $contact['icon'] }} display-6 text-primary"></i>
                                </div>
                                <h5 class="card-title">{{ $contact['title'] }}</h5>
                                <p class="card-text">{{ $contact['value'] }}</p>
                                @if(isset($contact['description']))
                                <small class="text-muted">{{ $contact['description'] }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-light p-4 rounded">
                    <h3 class="h4 mb-3"><i class="bi bi-clock-history text-success me-2"></i>График работы</h3>
                    <p class="mb-2"><strong>Понедельник - Пятница:</strong> 9:00 - 18:00</p>
                    <p class="mb-2"><strong>Суббота:</strong> 10:00 - 15:00</p>
                    <p class="mb-0"><strong>Воскресенье:</strong> Выходной</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection