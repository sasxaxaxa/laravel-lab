<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function contacts()
    {
        $contacts = [
            [
                'title' => 'Адрес',
                'value' => 'г. Москва, ул. Примерная, д. 123',
                'icon' => 'bi-geo-alt',
                'description' => 'Ближайшее метро: "Технологический институт"'
            ],
            [
                'title' => 'Телефон',
                'value' => '+7 (999) 123-45-67',
                'icon' => 'bi-phone',
                'description' => 'Звонки принимаются в рабочее время'
            ],
            [
                'title' => 'Email',
                'value' => 'info@mysite.ru',
                'icon' => 'bi-envelope',
                'description' => 'Ответим в течение 24 часов'
            ],
            [
                'title' => 'Техническая поддержка',
                'value' => 'support@mysite.ru',
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
                'value' => '@mysite_official',
                'icon' => 'bi-telegram',
                'description' => 'Telegram канал'
            ],
        ];

        return view('contacts', compact('contacts'));
    }
}