<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Article;

return new class extends Migration
{
    public function up()
    {
        // Убедимся, что поле user_id существует
        if (!Schema::hasColumn('articles', 'user_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id');
            });
        }
        
        // Назначим user_id для существующих статей
        $defaultUser = User::first();
        if ($defaultUser) {
            Article::whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        }
        
        // Делаем поле обязательным после заполнения
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    public function down()
    {
        // В откате просто оставляем как nullable
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }
};