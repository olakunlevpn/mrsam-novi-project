<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FaqCategory extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'slug',
        'name',
        'order_column',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('order_column');
    }
}
