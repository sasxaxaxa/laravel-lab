{{-- resources/views/emails/articles/new.blade.php --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая статья на сайте</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .article-info {
            background-color: white;
            border-left: 4px solid #4a90e2;
            padding: 15px;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background-color: #4a90e2;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📰 Новая статья на сайте</h1>
        <p>{{ config('app.name') }} - Система уведомлений</p>
    </div>

    <div class="content">
        <h2>Уважаемый модератор,</h2>
        <p>На сайте была добавлена новая статья, требующая вашего внимания.</p>

        <div class="article-info">
            <h3 style="color: #4a90e2; margin-top: 0;">{{ $article->title }}</h3>
            
            <p><strong>Автор:</strong> {{ $author->name }} ({{ $author->email }})</p>
            
            <p><strong>Категория:</strong> 
                <span class="badge">{{ $article->category }}</span>
            </p>
            
            <p><strong>Дата создания:</strong> {{ $article->created_at->format('d.m.Y H:i') }}</p>
            
            <p><strong>Краткое содержание:</strong></p>
            <p style="background-color: #f5f5f5; padding: 10px; border-radius: 3px;">
                {{ Str::limit(strip_tags($article->content), 200) }}
            </p>
            
            <p><strong>Статус:</strong> 
                @if($article->is_published)
                    <span style="color: green;">✅ Опубликована</span>
                @else
                    <span style="color: orange;">⏳ Ожидает публикации</span>
                @endif
            </p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $articleUrl }}" class="button" style="background-color: #4a90e2;">
                📖 Просмотреть статью
            </a>
            <a href="{{ $adminUrl }}" class="button" style="background-color: #34a853;">
                ⚙️ Панель модератора
            </a>
        </div>

        <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">⚠️ Действия модератора</h4>
            <p>Пожалуйста, проверьте статью на соответствие правилам сообщества и:</p>
            <ul>
                <li>Подтвердите публикацию</li>
                <li>Или отклоните с комментарием</li>
                <li>При необходимости отредактируйте</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Это автоматическое уведомление от системы {{ config('app.name') }}</p>
        <p>Вы получили это письмо, потому что являетесь модератором сайта.</p>
        <p>Если вы не хотите получать такие уведомления, обратитесь к администратору.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
    </div>
</body>
</html>