<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'type',
        'data',
        'order_column',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'data'         => 'array',
            'is_visible'   => 'boolean',
            'order_column' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
