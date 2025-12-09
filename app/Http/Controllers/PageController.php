<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('/pages/home');
    }

    public function about()
    {
        return view('pages/about');
    }

    public function contacts()
    {
        $contacts = [
            [
                'title' => 'Адрес',
                'value' => 'г. Москва, ул. Пряшникова, д. 2а',
                'icon' => 'bi-geo-alt',
                'description' => 'Ближайшее метро: "Коптево"'
            ],
            [
                'title' => 'Телефон',
                'value' => '+7 (999) 123-45-67',
                'icon' => 'bi-phone',
                'description' => 'Звонки принимаются в рабочее время'
            ],
            [
                'title' => 'Email',
                'value' => 'news@gmail.com',
                'icon' => 'bi-envelope',
                'description' => 'Ответим в течение 24 часов'
            ],
            [
                'title' => 'Техническая поддержка',
                'value' => 'support-news@gmail.com',
                'icon' => 'bi-headset',
                'description' => 'Круглосуточно'
            ],
            [
                'title' => 'Время ответа',
                'value' => '1-2 рабочих дня',
                'icon' => 'bi-clock',
                'description' => 'На письма отвечаем быстро'
            ],
            [
                'title' => 'Социальные сети',
                'value' => '@news_official',
                'icon' => 'bi-telegram',
                'description' => 'Telegram канал'
            ],
        ];

        return view('pages/contacts', compact('contacts'));
    }
}