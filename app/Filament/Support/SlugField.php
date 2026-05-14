<?php

namespace App\Filament\Support;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

/**
 * Centralised configuration for the "type the title, watch the slug fill in"
 * pattern used across every CMS resource form.
 *
 * - Source field (title/name): writes to the slug field on every keystroke
 *   (debounced), but only while creating, so we never silently rewrite the
 *   URL of an already-published record on edit.
 * - Slug field: always read-only at the form level so admins can't drift it
 *   away from the source. Still dehydrated so the server persists it, and
 *   still subject to whatever uniqueness rule the caller layers on top.
 */
class SlugField
{
    /**
     * Wire a title/name TextInput so its keystrokes regenerate the slug.
     */
    public static function source(TextInput $input, string $slugFieldName = 'slug'): TextInput
    {
        return $input
            ->live(debounce: 500)
            ->afterStateUpdated(function (string $operation, ?string $state, Set $set) use ($slugFieldName): void {
                if ($operation !== 'create') {
                    return;
                }
                $set($slugFieldName, Str::slug($state ?? ''));
            });
    }

    /**
     * Apply read-only + dehydrated semantics to the slug TextInput.
     * Caller is still responsible for ->unique(), ->required(), ->maxLength(),
     * and any per-form scoping (e.g. unique within animal).
     */
    public static function slug(TextInput $input): TextInput
    {
        return $input
            ->readOnly()
            ->dehydrated()
            ->alphaDash();
    }
}
