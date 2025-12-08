<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Policies\ArticlePolicy;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('is-moderator', function (User $user) {
            return $user->isModerator();
        });

        Gate::define('is-reader', function (User $user) {
            return $user->isReader();
        });

        Gate::define('manage-articles', function (User $user) {
            return $user->isModerator();
        });

        Gate::define('manage-comments', function (User $user) {
            return $user->isModerator();
        });

        Gate::define('create-article', function (User $user) {
            return $user->isModerator();
        });

        Gate::before(function (User $user, string $ability) {
            if ($user->isModerator()) {
                return true;
            }
        });
    }
}