<?php

namespace App\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Attach a SeoMeta polymorphically. Hosting model just adds `use HasSeo;`.
 */
trait HasSeo
{
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function seo(): SeoMeta
    {
        return $this->seoMeta ?? $this->seoMeta()->make();
    }

    public function setSeo(array $attributes): SeoMeta
    {
        $existing = $this->seoMeta;
        if ($existing) {
            $existing->update($attributes);
            return $existing->fresh();
        }
        return $this->seoMeta()->create($attributes);
    }
}
