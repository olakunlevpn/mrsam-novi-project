<?php

namespace App\Models;

use App\Support\AssetUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'noindex',
        'robots',
        'schema_jsonld',
    ];

    protected function casts(): array
    {
        return [
            'noindex'       => 'boolean',
            'schema_jsonld' => 'array',
        ];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Browser-loadable og:image URL. Routes uploaded paths through the
     * public disk while passing absolute URLs straight through.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        return AssetUrl::resolve($this->og_image);
    }
}
