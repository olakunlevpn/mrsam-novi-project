<?php

namespace App\Models;

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
}
