<?php

namespace App\Models;

use App\Concerns\HasSeo;
use App\Support\AssetUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Animal extends Model implements HasMedia
{
    use HasFactory;
    use HasSeo;
    use HasSlug;
    use InteractsWithMedia;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'hero_image',
        'order_column',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')->singleFile();
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Browser-loadable URL for the hero image. Resolves uploaded paths
     * via the public disk and passes legacy absolute paths through.
     */
    public function getHeroImageUrlAttribute(): ?string
    {
        return AssetUrl::resolve($this->hero_image);
    }
}
