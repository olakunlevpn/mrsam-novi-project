<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

/**
 * Reusable SEO meta editor for any model that uses the HasSeo trait.
 *
 * Adds a collapsed "SEO & Social" section to a Filament form. Persists
 * via the polymorphic `seoMeta` morphOne relation, so the host model only
 * needs `use HasSeo;`.
 */
class SeoMetaSection
{
    public static function make(): Section
    {
        return Section::make(__('cms.pages.section.seo'))
            ->description(__('cms.pages.section.seo_description'))
            ->relationship('seoMeta')
            ->columns(2)
            ->collapsed()
            ->collapsible()
            ->components([
                TextInput::make('title')
                    ->label(__('cms.pages.seo.title'))
                    ->maxLength(191)
                    ->helperText(__('cms.pages.seo.title_help')),
                TextInput::make('canonical_url')
                    ->label(__('cms.pages.seo.canonical_url'))
                    ->url()
                    ->maxLength(500),
                Textarea::make('meta_description')
                    ->label(__('cms.pages.seo.meta_description'))
                    ->maxLength(500)
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('og_title')
                    ->label(__('cms.pages.seo.og_title'))
                    ->maxLength(191),
                TextInput::make('og_image')
                    ->label(__('cms.pages.seo.og_image'))
                    ->url()
                    ->maxLength(500),
                Textarea::make('og_description')
                    ->label(__('cms.pages.seo.og_description'))
                    ->maxLength(500)
                    ->rows(2)
                    ->columnSpanFull(),
                Toggle::make('noindex')
                    ->label(__('cms.pages.seo.noindex'))
                    ->helperText(__('cms.pages.seo.noindex_help')),
                TextInput::make('robots')
                    ->label(__('cms.pages.seo.robots'))
                    ->maxLength(191)
                    ->placeholder('index, follow'),
            ]);
    }
}
