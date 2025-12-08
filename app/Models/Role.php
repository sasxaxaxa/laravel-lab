<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    const MODERATOR = 'moderator';
    const READER = 'reader';

    public function isModerator(): bool
    {
        return $this->name === self::MODERATOR;
    }

    public function isReader(): bool
    {
        return $this->name === self::READER;
    }
}