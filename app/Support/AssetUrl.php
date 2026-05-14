<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Resolves user-uploaded asset paths to browser-loadable URLs.
 *
 * Filament's FileUpload writes relative paths like
 * `posts/covers/abc123.jpg` onto the public disk. We sometimes still
 * carry legacy values like `/assets/images/foo.png` (absolute web
 * paths) or `https://cdn.example.com/foo.png` (external URLs). This
 * helper normalises all three shapes into something an `<img src>`
 * can consume.
 */
class AssetUrl
{
    /**
     * Resolve a setting/field value to a URL. Falls back to $fallback
     * when $value is blank.
     */
    public static function resolve(?string $value, ?string $fallback = null): ?string
    {
        $value = is_string($value) ? trim($value) : '';
        if ($value === '') {
            return $fallback;
        }

        if (preg_match('#^(https?:)?//#i', $value) === 1 || str_starts_with($value, '/')) {
            return $value;
        }

        return Storage::disk('public')->url($value);
    }
}
