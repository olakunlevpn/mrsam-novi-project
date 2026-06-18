<?php

namespace App\Cms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

/**
 * Per-block-type Filament field schemas. Returned as plain arrays of
 * field components. Schemas describe the editable shape of a block's
 * `data` JSON column.
 *
 * Field state paths are flat (e.g. `headline` not `data.headline`).
 * `PageForm` flattens the row's `data` array into top-level keys for
 * fill and re-packs them back into `data` on save, so each TextInput
 * binds to a normal state slot rather than a dot-notation child path.
 */
class BlockSchemas
{
    /**
     * Keys reserved by the page_blocks row that must not be repacked
     * into the `data` JSON column.
     *
     * @var array<int, string>
     */
    public const ROW_KEYS = ['type', 'is_visible', 'order_column'];

    /**
     * Hero block: subtitle, headline, video src, CTA URL/label.
     *
     * @return array<int, mixed>
     */
    public static function hero(): array
    {
        return [
            TextInput::make('subtitle')
                ->label(__('cms.block_fields.hero.subtitle'))
                ->maxLength(255),
            TextInput::make('headline')
                ->label(__('cms.block_fields.hero.headline'))
                ->maxLength(255),
            TextInput::make('video_src')
                ->label(__('cms.block_fields.hero.video_src'))
                ->placeholder('/assets/videos/hero.mp4'),
            TextInput::make('cta_label')
                ->label(__('cms.block_fields.hero.cta_label'))
                ->maxLength(64),
            TextInput::make('cta_url')
                ->label(__('cms.block_fields.hero.cta_url'))
                ->maxLength(500),
        ];
    }

    /**
     * Page header: background image + title.
     *
     * @return array<int, mixed>
     */
    public static function pageHeader(): array
    {
        return [
            TextInput::make('title')
                ->label(__('cms.block_fields.page_header.title'))
                ->maxLength(255)
                ->columnSpanFull(),
            FileUpload::make('background_image')
                ->label(__('cms.block_fields.page_header.background_image'))
                ->image()
                ->disk('public')
                ->directory('blocks/page-header')
                ->imageEditor()
                ->maxSize(8192)
                ->columnSpanFull(),
        ];
    }

    /**
     * Breadcrumb: just the current-page label.
     *
     * @return array<int, mixed>
     */
    public static function breadcrumb(): array
    {
        return [
            TextInput::make('label')
                ->label(__('cms.block_fields.breadcrumb.label'))
                ->maxLength(191)
                ->columnSpanFull(),
        ];
    }

    /**
     * CTA booking section: tagline, title, button label, form action, image URLs.
     *
     * @return array<int, mixed>
     */
    public static function ctaBooking(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.cta_booking.tagline'))
                ->maxLength(64),
            TextInput::make('submit_label')
                ->label(__('cms.block_fields.cta_booking.submit_label'))
                ->maxLength(64),
            TextInput::make('shadow_title')
                ->label(__('cms.block_fields.cta_booking.shadow_title'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.cta_booking.title'))
                ->helperText(__('cms.block_fields.cta_booking.title_help'))
                ->maxLength(500)
                ->columnSpanFull(),
            TextInput::make('form_action')
                ->label(__('cms.block_fields.cta_booking.form_action'))
                ->url()
                ->maxLength(500)
                ->columnSpanFull(),
            TextInput::make('form_subject')
                ->label(__('cms.block_fields.cta_booking.form_subject'))
                ->maxLength(255)
                ->columnSpanFull(),
            FileUpload::make('background_image')
                ->label(__('cms.block_fields.cta_booking.background_image'))
                ->image()
                ->disk('public')
                ->directory('blocks/cta-booking')
                ->imageEditor()
                ->maxSize(8192)
                ->columnSpanFull(),
            FileUpload::make('image_bg')
                ->label(__('cms.block_fields.cta_booking.image_bg'))
                ->image()
                ->disk('public')
                ->directory('blocks/cta-booking')
                ->imageEditor()
                ->maxSize(4096)
                ->columnSpanFull(),
            FileUpload::make('image_vet')
                ->label(__('cms.block_fields.cta_booking.image_vet'))
                ->image()
                ->disk('public')
                ->directory('blocks/cta-booking')
                ->imageEditor()
                ->maxSize(4096)
                ->columnSpanFull(),
        ];
    }

    /**
     * Partners carousel: title + repeater of {logo, url, alt}.
     *
     * @return array<int, mixed>
     */
    public static function partnersCarousel(): array
    {
        return [
            TextInput::make('title')
                ->label(__('cms.block_fields.partners_carousel.title'))
                ->maxLength(500)
                ->helperText(__('cms.block_fields.partners_carousel.title_help'))
                ->columnSpanFull(),
            Repeater::make('partners')
                ->label(__('cms.block_fields.partners_carousel.partners'))
                ->components([
                    FileUpload::make('logo')
                        ->label(__('cms.block_fields.partners_carousel.logo'))
                        ->required()
                        ->image()
                        ->disk('public')
                        ->directory('blocks/partners')
                        ->imageEditor()
                        ->maxSize(2048),
                    TextInput::make('url')
                        ->label(__('cms.block_fields.partners_carousel.url'))
                        ->url()
                        ->maxLength(500),
                    TextInput::make('alt')
                        ->label(__('cms.block_fields.partners_carousel.alt'))
                        ->maxLength(191)
                        ->placeholder('Partner Logo'),
                ])
                ->columns(3)
                ->reorderable()
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['alt'] ?? $state['logo'] ?? null)
                ->defaultItems(0)
                ->addActionLabel(__('cms.block_fields.partners_carousel.add'))
                ->columnSpanFull(),
        ];
    }

    /**
     * Testimonials: tagline, title + repeater of {name, designation, image, content, rating}.
     *
     * @return array<int, mixed>
     */
    public static function testimonials(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.testimonials.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.testimonials.title'))
                ->maxLength(500),
            Repeater::make('items')
                ->label(__('cms.block_fields.testimonials.items'))
                ->components([
                    TextInput::make('name')
                        ->label(__('cms.block_fields.testimonials.name'))
                        ->required()
                        ->maxLength(191),
                    TextInput::make('designation')
                        ->label(__('cms.block_fields.testimonials.designation'))
                        ->maxLength(191),
                    FileUpload::make('image')
                        ->label(__('cms.block_fields.testimonials.image'))
                        ->image()
                        ->disk('public')
                        ->directory('blocks/testimonials')
                        ->imageEditor()
                        ->maxSize(4096),
                    Select::make('rating')
                        ->label(__('cms.block_fields.testimonials.rating'))
                        ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                        ->default(5)
                        ->native(false),
                    Textarea::make('content')
                        ->label(__('cms.block_fields.testimonials.content'))
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->reorderable()
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                ->defaultItems(0)
                ->addActionLabel(__('cms.block_fields.testimonials.add'))
                ->columnSpanFull(),
        ];
    }

    /**
     * Contact form: action URL, subject, success message, submit label.
     *
     * @return array<int, mixed>
     */
    public static function contactForm(): array
    {
        return [
            TextInput::make('action_url')
                ->label(__('cms.block_fields.contact_form.action_url'))
                ->helperText(__('cms.block_fields.contact_form.action_url_help'))
                ->maxLength(500)
                ->columnSpanFull(),
            TextInput::make('subject')
                ->label(__('cms.block_fields.contact_form.subject'))
                ->maxLength(191),
            TextInput::make('submit_label')
                ->label(__('cms.block_fields.contact_form.submit_label'))
                ->maxLength(64),
            Textarea::make('success_message')
                ->label(__('cms.block_fields.contact_form.success_message'))
                ->rows(2)
                ->maxLength(500)
                ->columnSpanFull(),
        ];
    }

    /**
     * Contact map: just the embed URL.
     *
     * @return array<int, mixed>
     */
    public static function contactMap(): array
    {
        return [
            TextInput::make('embed_url')
                ->label(__('cms.block_fields.contact_map.embed_url'))
                ->helperText(__('cms.block_fields.contact_map.embed_url_help'))
                ->maxLength(2000)
                ->columnSpanFull(),
        ];
    }

    /**
     * FAQ accordion intro section. The accordion items themselves come from
     * the `faqs` table managed under /admin/faqs.
     *
     * @return array<int, mixed>
     */
    public static function faqAccordion(): array
    {
        return [
            TextInput::make('eyebrow')
                ->label(__('cms.block_fields.faq_accordion.eyebrow'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.faq_accordion.title'))
                ->maxLength(500),
            Textarea::make('subtitle')
                ->label(__('cms.block_fields.faq_accordion.subtitle'))
                ->rows(2)
                ->maxLength(500)
                ->columnSpanFull(),
        ];
    }
}
