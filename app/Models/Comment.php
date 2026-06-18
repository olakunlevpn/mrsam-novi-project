<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'body',
        'status',
        'ip',
        'user_agent',
    ];

    /**
     * Whether new comments are held for admin approval. Driven by the
     * `comments.moderation` setting (Settings > Comments); defaults to on.
     */
    public static function moderationEnabled(): bool
    {
        return (bool) Setting::get('comments.moderation', true);
    }

    /**
     * Status a freshly posted comment should take: 'pending' when moderation
     * is on, 'approved' (visible immediately) when it is off.
     */
    public static function defaultStatus(): string
    {
        return self::moderationEnabled() ? 'pending' : 'approved';
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
