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
        // 1. Добавляем поле user_id если его нет
        if (!Schema::hasColumn('articles', 'user_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id');
            });
        }
        
        // 2. Назначаем user_id для существующих статей
        $defaultUser = User::first();
        if ($defaultUser) {
            Article::whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        } else {
            // Если нет пользователей, создаем одного
            $defaultUser = User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            Article::whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        }
        
        // 3. Делаем поле обязательным
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
        
        // 4. Добавляем внешний ключ
        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};