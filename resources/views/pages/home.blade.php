@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 fw-bold text-primary mb-4">Добро пожаловать!</h1>
    <p class="lead fs-4 mb-4">
        Это учебный проект по разработке на Laravel. Здесь мы изучаем работу с маршрутизатором и шаблонизатором Blade.
    </p>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="card-title mb-4"><i class="bi bi-newspaper text-success me-2"></i>Что будет на сайте?</h3>
                    <ul class="list-group list-group-flush text-start">
                        <li class="list-group-item border-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Главная страница с приветствием
                        </li>
                        <li class="list-group-item border-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Страница "О нас" с информацией
                        </li>
                        <li class="list-group-item border-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Страница "Контакты" с динамическими данными
                        </li>
                        <li class="list-group-item border-0">
                            <i class="bi bi-clock text-warning me-2"></i>
                            В будущем - список новостей
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection