@extends('layouts.app')

@section('title', 'О нас')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <h1 class="card-title text-center mb-5 text-primary">
                    <i class="bi bi-info-circle me-2"></i>О нашей компании
                </h1>
                
                <div class="row mb-5">
                    <div class="col-md-6 mb-4">
                        <div class="p-4 bg-light rounded">
                            <h3 class="h4 mb-3"><i class="bi bi-rocket-takeoff text-primary me-2"></i>Наша миссия</h3>
                            <p class="mb-0">
                                Мы создаем качественные учебные материалы и проекты для студентов, 
                                изучающих веб-разработку на фреймворке Laravel.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="p-4 bg-light rounded">
                            <h3 class="h4 mb-3"><i class="bi bi-eye text-success me-2"></i>Наше видение</h3>
                            <p class="mb-0">
                                Стать лучшим образовательным ресурсом для начинающих PHP-разработчиков 
                                в русскоязычном сообществе.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="h3 mb-4"><i class="bi bi-people text-warning me-2"></i>Наша команда</h2>
                    <p>
                        Наша команда состоит из опытных разработчиков и преподавателей, 
                        которые имеют многолетний опыт работы с Laravel и другими современными технологиями.
                    </p>
                    <p>
                        Мы верим в практический подход к обучению, поэтому все наши материалы 
                        включают реальные проекты и примеры кода.
                    </p>
                </div>

                <div class="bg-light p-4 rounded">
                    <h3 class="h4 mb-3"><i class="bi bi-bar-chart text-info me-2"></i>Наши достижения</h3>
                    <ul class="mb-0">
                        <li>Более 1000 студентов прошли наши курсы</li>
                        <li>20+ учебных проектов с открытым исходным кодом</li>
                        <li>Активное сообщество разработчиков</li>
                        <li>Регулярные обновления материалов</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection