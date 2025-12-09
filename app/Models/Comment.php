<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'article_id',
        'user_id',
        'status',
        'is_approved',
        'is_rejected',
        'moderated_by',
        'moderated_at',
        'rejection_reason',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_rejected' => 'boolean',
        'moderated_at' => 'datetime',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                    ->orWhere(function($q) {
                        $q->where('is_approved', false)
                          ->where('is_rejected', false);
                    });
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')
                    ->orWhere('is_approved', true);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected')
                    ->orWhere('is_rejected', true);
    }

    public function scopeForModeration($query)
    {
        return $this->pending();
    }

    public function isPending()
    {
        return $this->status === 'pending' || (!$this->is_approved && !$this->is_rejected);
    }

    public function isApproved()
    {
        return $this->status === 'approved' || $this->is_approved;
    }

    public function isRejected()
    {
        return $this->status === 'rejected' || $this->is_rejected;
    }

    public function approve(User $moderator)
    {
        $this->update([
            'status' => 'approved',
            'is_approved' => true,
            'is_rejected' => false,
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
        ]);
    }

    public function reject(User $moderator, $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'is_approved' => false,
            'is_rejected' => true,
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }
}