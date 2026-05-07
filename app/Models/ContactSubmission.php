<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip',
        'user_agent',
        'status',
    ];

    protected $attributes = [
        'status' => 'new',
    ];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }
}
