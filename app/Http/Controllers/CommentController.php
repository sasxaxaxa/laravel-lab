<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $comment = $article->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'is_approved' => auth()->user()->isModerator(), // Модераторы сразу одобрены
        ]);

        return back()->with('success', 'Комментарий добавлен' . 
            (auth()->user()->isModerator() ? '' : ' и ожидает модерации'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $comment->update($validated);

        return back()->with('success', 'Комментарий обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Комментарий удален');
    }

    /**
     * Approve comment (moderator only).
     */
    public function approve(Comment $comment)
    {
        Gate::authorize('approve', $comment);

        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Комментарий одобрен');
    }

    /**
     * List pending comments (moderator only).
     */
    public function pending()
    {
        Gate::authorize('manage-comments');

        $comments = Comment::pending()
            ->with(['user', 'article'])
            ->latest()
            ->paginate(20);

        return view('pages.comments.pending', compact('comments'));
    }
}