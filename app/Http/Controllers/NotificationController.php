<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Конструктор - применяем middleware аутентификации
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Отметить уведомление как прочитанное и перенаправить на статью
     */
    public function read(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if (!$notification) {
            return redirect()->route('articles.index')
                ->with('error', 'Уведомление не найдено');
        }
        
        // Помечаем как прочитанное
        $notification->markAsRead();
        
        // Получаем данные уведомления
        $data = $notification->data;
        $articleId = $data['article_id'] ?? null;
        
        if ($articleId) {
            return redirect()->route('articles.show', $articleId)
                ->with('success', 'Уведомление отмечено как прочитанное');
        }
        
        return redirect()->route('articles.index')
            ->with('error', 'Не удалось найти статью');
    }

    /**
     * Показать все уведомления пользователя
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);
        
        return view('pages.notifications.index', compact('notifications'));
    }

    /**
     * Отметить все уведомления как прочитанные
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return redirect()->back()
            ->with('success', 'Все уведомления отмечены как прочитанные');
    }

    /**
     * Удалить все уведомления
     */
    public function destroyAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'Все уведомления удалены');
    }

    /**
     * Удалить конкретное уведомление
     */
    public function destroy($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->delete();
            return redirect()->back()
                ->with('success', 'Уведомление удалено');
        }
        
        return redirect()->back()
            ->with('error', 'Уведомление не найдено');
    }

    /**
     * Получить количество непрочитанных уведомлений (для AJAX)
     */
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Получить список непрочитанных уведомлений (для AJAX)
     */
    public function unreadList()
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => []]);
        }
        
        $notifications = Auth::user()->unreadNotifications()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? 'Новое уведомление',
                    'article_title' => $notification->data['article_title'] ?? 'Новая статья',
                    'url' => route('notifications.read', $notification->id),
                    'time' => $notification->created_at->diffForHumans(),
                ];
            });
        
        return response()->json(['notifications' => $notifications]);
    }
}