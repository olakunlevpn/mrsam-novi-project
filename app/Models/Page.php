<?php

namespace App\Models;

use App\Concerns\HasBlocks;
use App\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasFactory;
    use HasSlug;
    use HasBlocks;
    use HasSeo;

    protected $fillable = [
        'slug',
        'title',
        'layout',
        'status',
        'published_at',
        'is_homepage',
        'order_column',
    ];

    protected function casts(): array
    {
        return [
            'status'       => 'string',
            'published_at' => 'datetime',
            'is_homepage'  => 'boolean',
            'order_column' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('order_column');
    }

    public function visibleBlocks(): HasMany
    {
        return $this->blocks()->where('is_visible', true);
    }
}
