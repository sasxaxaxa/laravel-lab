<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'author',
        'image',
        'views',
        'is_published',
        'user_id',
    ];

    protected $attributes = [
        'user_id' => 1, // Значение по умолчанию
    ];

    public static function rules($id = null)
    {
        return [
            'title' => 'required|string|min:5|max:200',
            'slug' => 'required|string|unique:articles,slug' . ($id ? ",$id" : ''),
            'content' => 'required|string|min:20',
            'category' => 'required|string|in:politics,sports,technology,entertainment,business,health',
            'author' => 'required|string|min:3|max:100',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ];
    }

    public static function messages()
    {
        return [
            'title.required' => 'Заголовок обязателен',
            'title.min' => 'Заголовок должен быть не менее 5 символов',
            'slug.required' => 'Slug обязателен',
            'slug.unique' => 'Такой slug уже существует',
            'content.required' => 'Содержание обязательно',
            'content.min' => 'Содержание должно быть не менее 20 символов',
            'category.required' => 'Категория обязательна',
            'category.in' => 'Выберите существующую категорию',
            'author.required' => 'Автор обязателен',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}