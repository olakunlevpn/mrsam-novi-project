<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'route_name',
        'linkable_type',
        'linkable_id',
        'target',
        'order_column',
    ];

    protected function casts(): array
    {
        return [
            'order_column' => 'integer',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order_column');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Resolve a renderable URL for this item.
     *
     * Priority: explicit url > named route (if registered) > '#' fallback.
     */
    public function getResolvedUrlAttribute(): string
    {
        if (! empty($this->url)) {
            return $this->url;
        }

        if (! empty($this->route_name) && Route::has($this->route_name)) {
            return route($this->route_name);
        }

        return '#';
    }

    /**
     * Whether this item or any of its (loaded) children matches the current route.
     */
    public function isCurrent(): bool
    {
        if ($this->route_name && request()->routeIs($this->route_name)) {
            return true;
        }

        if ($this->relationLoaded('children')) {
            foreach ($this->children as $child) {
                if ($child->isCurrent()) {
                    return true;
                }
            }
        }

        return false;
    }
}
