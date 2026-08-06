<?php

namespace App\Cms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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
                ->maxSize(102400)
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
                ->maxSize(102400)
                ->columnSpanFull(),
            FileUpload::make('image_bg')
                ->label(__('cms.block_fields.cta_booking.image_bg'))
                ->image()
                ->disk('public')
                ->directory('blocks/cta-booking')
                ->imageEditor()
                ->maxSize(102400)
                ->columnSpanFull(),
            FileUpload::make('image_vet')
                ->label(__('cms.block_fields.cta_booking.image_vet'))
                ->image()
                ->disk('public')
                ->directory('blocks/cta-booking')
                ->imageEditor()
                ->maxSize(102400)
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
                        ->maxSize(102400),
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
     * Testimonials section heading: tagline + title. The reviews themselves are
     * managed in the Testimonials resource (App\Models\Testimonial), not here.
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

    /**
     * Home: feature grid of 4 cards {title, text, icon}.
     *
     * @return array<int, mixed>
     */
    public static function featureGrid(): array
    {
        $fields = [];
        for ($i = 1; $i <= 4; $i++) {
            $fields[] = TextInput::make("card_{$i}_title")
                ->label(__('cms.block_fields.feature_grid.card_title', ['n' => $i]))
                ->maxLength(255);
            $fields[] = TextInput::make("card_{$i}_icon")
                ->label(__('cms.block_fields.feature_grid.card_icon', ['n' => $i]))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191);
            $fields[] = Textarea::make("card_{$i}_text")
                ->label(__('cms.block_fields.feature_grid.card_text', ['n' => $i]))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull();
        }

        return $fields;
    }

    /**
     * About: feature grid of 4 cards {title, text, icon} + shared card background.
     *
     * @return array<int, mixed>
     */
    public static function featureGridAbout(): array
    {
        $fields = [
            self::imageUpload('card_bg', 'feature-grid-about')
                ->label(__('cms.block_fields.feature_grid_about.card_bg'))
                ->columnSpanFull(),
        ];
        for ($i = 1; $i <= 4; $i++) {
            $fields[] = TextInput::make("card_{$i}_title")
                ->label(__('cms.block_fields.feature_grid_about.card_title', ['n' => $i]))
                ->maxLength(255);
            $fields[] = TextInput::make("card_{$i}_icon")
                ->label(__('cms.block_fields.feature_grid_about.card_icon', ['n' => $i]))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191);
            $fields[] = Textarea::make("card_{$i}_text")
                ->label(__('cms.block_fields.feature_grid_about.card_text', ['n' => $i]))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull();
        }

        return $fields;
    }

    /**
     * Home: about intro — text, stats, bullets, two images.
     *
     * @return array<int, mixed>
     */
    public static function aboutIntro(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.about_intro.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.about_intro.title'))
                ->maxLength(255),
            Textarea::make('paragraph_1')
                ->label(__('cms.block_fields.about_intro.paragraph_1'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('quality_title')
                ->label(__('cms.block_fields.about_intro.quality_title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(255)
                ->columnSpanFull(),
            TextInput::make('stat_value')
                ->label(__('cms.block_fields.about_intro.stat_value'))
                ->maxLength(64),
            TextInput::make('stat_label')
                ->label(__('cms.block_fields.about_intro.stat_label'))
                ->maxLength(191),
            Textarea::make('paragraph_2')
                ->label(__('cms.block_fields.about_intro.paragraph_2'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('bullet_1')
                ->label(__('cms.block_fields.about_intro.bullet_1'))
                ->maxLength(500),
            TextInput::make('bullet_2')
                ->label(__('cms.block_fields.about_intro.bullet_2'))
                ->maxLength(500),
            TextInput::make('bullet_3')
                ->label(__('cms.block_fields.about_intro.bullet_3'))
                ->maxLength(500)
                ->columnSpanFull(),
            self::imageUpload('image_main', 'about-intro')
                ->label(__('cms.block_fields.about_intro.image_main')),
            self::imageUpload('image_thumb', 'about-intro')
                ->label(__('cms.block_fields.about_intro.image_thumb')),
        ];
    }

    /**
     * Home: species cards — heading, intro, 3 cards {label, image}.
     *
     * @return array<int, mixed>
     */
    public static function speciesCards(): array
    {
        $fields = [
            TextInput::make('heading')
                ->label(__('cms.block_fields.species_cards.heading'))
                ->maxLength(191),
            Textarea::make('intro')
                ->label(__('cms.block_fields.species_cards.intro'))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull(),
        ];
        for ($i = 1; $i <= 3; $i++) {
            $fields[] = TextInput::make("card_{$i}_label")
                ->label(__('cms.block_fields.species_cards.card_label', ['n' => $i]))
                ->maxLength(191);
            $fields[] = self::imageUpload("card_{$i}_image", 'species-cards')
                ->label(__('cms.block_fields.species_cards.card_image', ['n' => $i]));
        }

        return $fields;
    }

    /**
     * Home: services summary — heading, background, 3 cards {title, text, icon}.
     *
     * @return array<int, mixed>
     */
    public static function servicesSummary(): array
    {
        $fields = [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.services_summary.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.services_summary.title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(500),
            self::imageUpload('background_image', 'services-summary')
                ->label(__('cms.block_fields.services_summary.background_image'))
                ->columnSpanFull(),
        ];
        for ($i = 1; $i <= 3; $i++) {
            $fields[] = TextInput::make("card_{$i}_title")
                ->label(__('cms.block_fields.services_summary.card_title', ['n' => $i]))
                ->maxLength(255);
            $fields[] = TextInput::make("card_{$i}_icon")
                ->label(__('cms.block_fields.services_summary.card_icon', ['n' => $i]))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191);
            $fields[] = Textarea::make("card_{$i}_text")
                ->label(__('cms.block_fields.services_summary.card_text', ['n' => $i]))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull();
        }

        return $fields;
    }

    /**
     * Home/Services: work process — heading, intro, 4 steps {title, text, icon}.
     *
     * @return array<int, mixed>
     */
    public static function workProcess(): array
    {
        $fields = [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.work_process.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.work_process.title'))
                ->maxLength(255),
            Textarea::make('paragraph')
                ->label(__('cms.block_fields.work_process.paragraph'))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull(),
        ];
        for ($i = 1; $i <= 4; $i++) {
            $fields[] = TextInput::make("step_{$i}_title")
                ->label(__('cms.block_fields.work_process.step_title', ['n' => $i]))
                ->maxLength(255);
            $fields[] = TextInput::make("step_{$i}_icon")
                ->label(__('cms.block_fields.work_process.step_icon', ['n' => $i]))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191);
            $fields[] = Textarea::make("step_{$i}_text")
                ->label(__('cms.block_fields.work_process.step_text', ['n' => $i]))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull();
        }

        return $fields;
    }

    /**
     * Home: benefits — text, two quality cards, bullets, CTA, two images.
     *
     * @return array<int, mixed>
     */
    public static function benefits(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.benefits.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.benefits.title'))
                ->maxLength(255),
            Textarea::make('paragraph_1')
                ->label(__('cms.block_fields.benefits.paragraph_1'))
                ->rows(2)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('card_1_title')
                ->label(__('cms.block_fields.benefits.card_1_title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(191),
            TextInput::make('card_2_title')
                ->label(__('cms.block_fields.benefits.card_2_title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(191),
            Textarea::make('paragraph_2')
                ->label(__('cms.block_fields.benefits.paragraph_2'))
                ->rows(2)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('bullet_1')
                ->label(__('cms.block_fields.benefits.bullet_1'))
                ->maxLength(500),
            TextInput::make('bullet_2')
                ->label(__('cms.block_fields.benefits.bullet_2'))
                ->maxLength(500),
            TextInput::make('bullet_3')
                ->label(__('cms.block_fields.benefits.bullet_3'))
                ->maxLength(500),
            TextInput::make('cta_label')
                ->label(__('cms.block_fields.benefits.cta_label'))
                ->maxLength(64),
            self::imageUpload('image_main', 'benefits')
                ->label(__('cms.block_fields.benefits.image_main')),
            self::imageUpload('image_thumb', 'benefits')
                ->label(__('cms.block_fields.benefits.image_thumb')),
        ];
    }

    /**
     * Home: stats bar — heading, paragraph, CTA, two fun-fact stats.
     *
     * @return array<int, mixed>
     */
    public static function statsBar(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.stats_bar.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.stats_bar.title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(500),
            Textarea::make('paragraph')
                ->label(__('cms.block_fields.stats_bar.paragraph'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('cta_label')
                ->label(__('cms.block_fields.stats_bar.cta_label'))
                ->maxLength(64),
            TextInput::make('cta_text')
                ->label(__('cms.block_fields.stats_bar.cta_text'))
                ->maxLength(191),
            TextInput::make('stat_1_value')
                ->label(__('cms.block_fields.stats_bar.stat_1_value'))
                ->maxLength(32),
            TextInput::make('stat_1_suffix')
                ->label(__('cms.block_fields.stats_bar.stat_1_suffix'))
                ->maxLength(16),
            TextInput::make('stat_1_title')
                ->label(__('cms.block_fields.stats_bar.stat_1_title'))
                ->maxLength(191),
            TextInput::make('stat_1_text')
                ->label(__('cms.block_fields.stats_bar.stat_1_text'))
                ->maxLength(500)
                ->columnSpanFull(),
            TextInput::make('stat_2_value')
                ->label(__('cms.block_fields.stats_bar.stat_2_value'))
                ->maxLength(32),
            TextInput::make('stat_2_title')
                ->label(__('cms.block_fields.stats_bar.stat_2_title'))
                ->maxLength(191),
            TextInput::make('stat_2_text')
                ->label(__('cms.block_fields.stats_bar.stat_2_text'))
                ->maxLength(500)
                ->columnSpanFull(),
        ];
    }

    /**
     * About: about detail — text, two quality cards, stat, bullets, CTA, images.
     *
     * @return array<int, mixed>
     */
    public static function aboutDetail(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.about_detail.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.about_detail.title'))
                ->maxLength(255),
            Textarea::make('paragraph')
                ->label(__('cms.block_fields.about_detail.paragraph'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('quality_card_1')
                ->label(__('cms.block_fields.about_detail.quality_card_1'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(191),
            TextInput::make('quality_card_2')
                ->label(__('cms.block_fields.about_detail.quality_card_2'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(191),
            TextInput::make('stat_value')
                ->label(__('cms.block_fields.about_detail.stat_value'))
                ->maxLength(32),
            TextInput::make('stat_label')
                ->label(__('cms.block_fields.about_detail.stat_label'))
                ->maxLength(191),
            TextInput::make('bullet_1')
                ->label(__('cms.block_fields.about_detail.bullet_1'))
                ->maxLength(500),
            TextInput::make('bullet_2')
                ->label(__('cms.block_fields.about_detail.bullet_2'))
                ->maxLength(500),
            TextInput::make('bullet_3')
                ->label(__('cms.block_fields.about_detail.bullet_3'))
                ->maxLength(500),
            TextInput::make('cta_label')
                ->label(__('cms.block_fields.about_detail.cta_label'))
                ->maxLength(64),
            self::imageUpload('image_main', 'about-detail')
                ->label(__('cms.block_fields.about_detail.image_main')),
            self::imageUpload('image_thumb', 'about-detail')
                ->label(__('cms.block_fields.about_detail.image_thumb')),
        ];
    }

    /**
     * About: benefits (about) — text, two cards, bullets, stat, side image.
     *
     * @return array<int, mixed>
     */
    public static function benefitsAbout(): array
    {
        return [
            TextInput::make('tagline')
                ->label(__('cms.block_fields.benefits_about.tagline'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.benefits_about.title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(500),
            Textarea::make('paragraph')
                ->label(__('cms.block_fields.benefits_about.paragraph'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('card_1_title')
                ->label(__('cms.block_fields.benefits_about.card_1_title'))
                ->maxLength(191),
            TextInput::make('card_2_title')
                ->label(__('cms.block_fields.benefits_about.card_2_title'))
                ->maxLength(191),
            Textarea::make('card_1_text')
                ->label(__('cms.block_fields.benefits_about.card_1_text'))
                ->rows(2)
                ->maxLength(1000),
            Textarea::make('card_2_text')
                ->label(__('cms.block_fields.benefits_about.card_2_text'))
                ->rows(2)
                ->maxLength(1000),
            TextInput::make('bullet_1')
                ->label(__('cms.block_fields.benefits_about.bullet_1'))
                ->maxLength(500),
            TextInput::make('bullet_2')
                ->label(__('cms.block_fields.benefits_about.bullet_2'))
                ->maxLength(500),
            TextInput::make('bullet_3')
                ->label(__('cms.block_fields.benefits_about.bullet_3'))
                ->maxLength(500),
            TextInput::make('bullet_4')
                ->label(__('cms.block_fields.benefits_about.bullet_4'))
                ->maxLength(500),
            TextInput::make('stat_value')
                ->label(__('cms.block_fields.benefits_about.stat_value'))
                ->maxLength(32),
            TextInput::make('stat_label')
                ->label(__('cms.block_fields.benefits_about.stat_label'))
                ->maxLength(191),
            self::imageUpload('image', 'benefits-about')
                ->label(__('cms.block_fields.benefits_about.image'))
                ->columnSpanFull(),
        ];
    }

    /**
     * About: journey & growth — heading, intro, shared label, 5 year cards.
     *
     * @return array<int, mixed>
     */
    public static function journeyGrowth(): array
    {
        $fields = [
            TextInput::make('title')
                ->label(__('cms.block_fields.journey_growth.title'))
                ->maxLength(191),
            Textarea::make('paragraph')
                ->label(__('cms.block_fields.journey_growth.paragraph'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
            TextInput::make('card_label')
                ->label(__('cms.block_fields.journey_growth.card_label'))
                ->maxLength(191)
                ->columnSpanFull(),
        ];
        for ($i = 1; $i <= 5; $i++) {
            $fields[] = TextInput::make("card_{$i}_year")
                ->label(__('cms.block_fields.journey_growth.card_year', ['n' => $i]))
                ->maxLength(32);
            $fields[] = TextInput::make("card_{$i}_value")
                ->label(__('cms.block_fields.journey_growth.card_value', ['n' => $i]))
                ->maxLength(32);
        }

        return $fields;
    }

    /**
     * About: customer growth — heading, intro, 5 progress bars, 4 summaries.
     *
     * @return array<int, mixed>
     */
    public static function customerGrowth(): array
    {
        $fields = [
            TextInput::make('eyebrow')
                ->label(__('cms.block_fields.customer_growth.eyebrow'))
                ->maxLength(64),
            TextInput::make('title')
                ->label(__('cms.block_fields.customer_growth.title'))
                ->maxLength(191),
            Textarea::make('paragraph')
                ->label(__('cms.block_fields.customer_growth.paragraph'))
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
        ];
        for ($i = 1; $i <= 5; $i++) {
            $fields[] = TextInput::make("bar_{$i}_year")
                ->label(__('cms.block_fields.customer_growth.bar_year', ['n' => $i]))
                ->maxLength(32);
            $fields[] = TextInput::make("bar_{$i}_label")
                ->label(__('cms.block_fields.customer_growth.bar_label', ['n' => $i]))
                ->maxLength(64);
            $fields[] = TextInput::make("bar_{$i}_width")
                ->label(__('cms.block_fields.customer_growth.bar_width', ['n' => $i]))
                ->maxLength(16);
        }
        for ($i = 1; $i <= 4; $i++) {
            $fields[] = TextInput::make("summary_{$i}_value")
                ->label(__('cms.block_fields.customer_growth.summary_value', ['n' => $i]))
                ->maxLength(32);
            $fields[] = TextInput::make("summary_{$i}_label")
                ->label(__('cms.block_fields.customer_growth.summary_label', ['n' => $i]))
                ->maxLength(191);
        }

        return $fields;
    }

    /**
     * Catalog: product catalog controls — search, sort, filter, category labels.
     *
     * @return array<int, mixed>
     */
    public static function productCatalog(): array
    {
        return [
            TextInput::make('search_placeholder')
                ->label(__('cms.block_fields.product_catalog.search_placeholder'))
                ->maxLength(191)
                ->columnSpanFull(),
            TextInput::make('sort_default_label')
                ->label(__('cms.block_fields.product_catalog.sort_default_label'))
                ->maxLength(64),
            TextInput::make('sort_newest_label')
                ->label(__('cms.block_fields.product_catalog.sort_newest_label'))
                ->maxLength(64),
            TextInput::make('sort_alpha_label')
                ->label(__('cms.block_fields.product_catalog.sort_alpha_label'))
                ->maxLength(64),
            TextInput::make('sort_popular_label')
                ->label(__('cms.block_fields.product_catalog.sort_popular_label'))
                ->maxLength(64),
            TextInput::make('filter_type_title')
                ->label(__('cms.block_fields.product_catalog.filter_type_title'))
                ->maxLength(64),
            TextInput::make('filter_animal_title')
                ->label(__('cms.block_fields.product_catalog.filter_animal_title'))
                ->maxLength(64),
            TextInput::make('cat_all_label')
                ->label(__('cms.block_fields.product_catalog.cat_all_label'))
                ->maxLength(64),
            TextInput::make('cat_cattle_label')
                ->label(__('cms.block_fields.product_catalog.cat_cattle_label'))
                ->maxLength(64),
            TextInput::make('cat_pigs_label')
                ->label(__('cms.block_fields.product_catalog.cat_pigs_label'))
                ->maxLength(64),
            TextInput::make('cat_poultry_label')
                ->label(__('cms.block_fields.product_catalog.cat_poultry_label'))
                ->maxLength(64),
        ];
    }

    /**
     * Services: service cards grid — 6 cards {title, text, cta, icon, image}.
     *
     * @return array<int, mixed>
     */
    public static function serviceCardsGrid(): array
    {
        $fields = [];
        for ($i = 1; $i <= 6; $i++) {
            $fields[] = TextInput::make("card_{$i}_title")
                ->label(__('cms.block_fields.service_cards_grid.card_title', ['n' => $i]))
                ->maxLength(255);
            $fields[] = TextInput::make("card_{$i}_icon")
                ->label(__('cms.block_fields.service_cards_grid.card_icon', ['n' => $i]))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191);
            $fields[] = Textarea::make("card_{$i}_text")
                ->label(__('cms.block_fields.service_cards_grid.card_text', ['n' => $i]))
                ->rows(2)
                ->maxLength(1000)
                ->columnSpanFull();
            $fields[] = TextInput::make("card_{$i}_cta")
                ->label(__('cms.block_fields.service_cards_grid.card_cta', ['n' => $i]))
                ->maxLength(64);
            $fields[] = self::imageUpload("card_{$i}_image", 'service-cards-grid')
                ->label(__('cms.block_fields.service_cards_grid.card_image', ['n' => $i]));
        }

        return $fields;
    }

    /**
     * Contact: contact info cards — heading, background, 3 info cards.
     *
     * @return array<int, mixed>
     */
    public static function contactInfoCards(): array
    {
        return [
            TextInput::make('title')
                ->label(__('cms.block_fields.contact_info_cards.title'))
                ->helperText(__('cms.block_fields.html_help'))
                ->maxLength(500)
                ->columnSpanFull(),
            TextInput::make('subtitle')
                ->label(__('cms.block_fields.contact_info_cards.subtitle'))
                ->maxLength(500)
                ->columnSpanFull(),
            self::imageUpload('background_image', 'contact-info-cards')
                ->label(__('cms.block_fields.contact_info_cards.background_image'))
                ->columnSpanFull(),
            TextInput::make('card_1_icon')
                ->label(__('cms.block_fields.contact_info_cards.card_1_icon'))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191),
            TextInput::make('card_1_title')
                ->label(__('cms.block_fields.contact_info_cards.card_1_title'))
                ->maxLength(191),
            Textarea::make('card_1_text')
                ->label(__('cms.block_fields.contact_info_cards.card_1_text'))
                ->rows(2)
                ->maxLength(500)
                ->columnSpanFull(),
            TextInput::make('card_2_icon')
                ->label(__('cms.block_fields.contact_info_cards.card_2_icon'))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191),
            TextInput::make('card_2_title')
                ->label(__('cms.block_fields.contact_info_cards.card_2_title'))
                ->maxLength(191),
            TextInput::make('card_2_phone_1')
                ->label(__('cms.block_fields.contact_info_cards.card_2_phone_1'))
                ->maxLength(64),
            TextInput::make('card_2_phone_2')
                ->label(__('cms.block_fields.contact_info_cards.card_2_phone_2'))
                ->maxLength(64),
            TextInput::make('card_3_icon')
                ->label(__('cms.block_fields.contact_info_cards.card_3_icon'))
                ->helperText(__('cms.block_fields.icon_help'))
                ->maxLength(191),
            TextInput::make('card_3_title')
                ->label(__('cms.block_fields.contact_info_cards.card_3_title'))
                ->maxLength(191),
            TextInput::make('card_3_email')
                ->label(__('cms.block_fields.contact_info_cards.card_3_email'))
                ->email()
                ->maxLength(191),
        ];
    }

    /**
     * Configured image upload shared by block schemas. Stored as a public-disk
     * relative path; blades resolve it via blockAsset() so uploaded files and
     * legacy /assets paths both render correctly.
     */
    private static function imageUpload(string $key, string $directory): FileUpload
    {
        return FileUpload::make($key)
            ->image()
            ->disk('public')
            ->directory("blocks/{$directory}")
            ->imageEditor()
            ->maxSize(102400);
    }
}
