<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Article;

return new class extends Migration
{
    public function up()
    {
        // Шаг 1: Добавить поле user_id как nullable
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });
        
        // Шаг 2: Заполнить поле данными
        $this->fillUserIds();
        
        // Шаг 3: Добавить внешний ключ (пока без NOT NULL ограничения)
        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Шаг 4: Обновить модель Article чтобы использовать default значение
        // Это будет сделано в коде приложения
    }
    
    private function fillUserIds()
    {
        // Получаем первого пользователя или создаем его
        $defaultUser = User::first();
        
        if (!$defaultUser) {
            $defaultUser = User::create([
                'name' => 'Default Author',
                'email' => 'author@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        
        // Обновляем все статьи, у которых нет user_id
        DB::table('articles')->whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        
        // Если все еще есть NULL значения (новые статьи во время миграции)
        DB::table('articles')->whereNull('user_id')->update(['user_id' => $defaultUser->id]);
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};