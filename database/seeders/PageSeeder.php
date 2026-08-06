<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageSeeder extends Seeder
{
    private array $pages = [
        [
            'slug'        => 'home',
            'title'       => 'Home',
            'layout'      => 'home',
            'is_homepage' => true,
            'blocks'      => [
                'hero',
                'feature-grid',
                'about-intro',
                'species-cards',
                'services-summary',
                'work-process',
                'benefits',
                'stats-bar',
                'cta-booking',
                'partners-carousel',
            ],
        ],
        [
            'slug'        => 'about',
            'title'       => 'About',
            'layout'      => 'about',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-about',
                'breadcrumb-about',
                'about-detail',
                'feature-grid-about',
                'benefits-about',
                'journey-growth',
                'customer-growth',
                'testimonials',
            ],
        ],
        [
            'slug'        => 'products',
            'title'       => 'Products',
            'layout'      => 'products',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-products',
                'breadcrumb-products',
                'product-catalog',
            ],
        ],
        [
            'slug'        => 'cattle',
            'title'       => 'Cattle',
            'layout'      => 'animal',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-cattle',
                'breadcrumb-cattle',
                'product-catalog',
            ],
        ],
        [
            'slug'        => 'pigs',
            'title'       => 'Pigs',
            'layout'      => 'animal',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-pigs',
                'breadcrumb-pigs',
                'product-catalog',
            ],
        ],
        [
            'slug'        => 'poultry',
            'title'       => 'Poultry',
            'layout'      => 'animal',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-poultry',
                'breadcrumb-poultry',
                'product-catalog',
            ],
        ],
        [
            'slug'        => 'services',
            'title'       => 'Services',
            'layout'      => 'services',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-services',
                'breadcrumb-services',
                'service-cards-grid',
            ],
        ],
        [
            'slug'        => 'contact',
            'title'       => 'Contact',
            'layout'      => 'contact',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-contact',
                'breadcrumb-contact',
                'contact-info-cards',
                'contact-form',
                'contact-map',
            ],
        ],
        [
            'slug'        => 'faq',
            'title'       => 'Your Questions Answered',
            'layout'      => 'faq',
            'is_homepage' => false,
            'blocks'      => [
                'page-header-faq',
                'breadcrumb-faq',
                'faq-accordion',
            ],
        ],
    ];

    /**
     * Which keys per block type are FileUpload image fields (copied onto the
     * public disk at seed time).
     *
     * @var array<string, array<int, string>>
     */
    private const IMAGE_KEYS = [
        'about-intro'        => ['image_main', 'image_thumb'],
        'species-cards'      => ['card_1_image', 'card_2_image', 'card_3_image'],
        'services-summary'   => ['background_image'],
        'benefits'           => ['image_main', 'image_thumb'],
        'about-detail'       => ['image_main', 'image_thumb'],
        'feature-grid-about' => ['card_bg'],
        'benefits-about'     => ['image'],
        'service-cards-grid' => ['card_1_image', 'card_2_image', 'card_3_image', 'card_4_image', 'card_5_image', 'card_6_image'],
        'contact-info-cards' => ['background_image'],
    ];

    /**
     * Default content per block type, matching each Blade partial's inline
     * fallbacks. Image values are bundled /assets paths, copied onto the public
     * disk by defaultDataFor(). Text values feed the typed Filament fields.
     *
     * @var array<string, array<string, mixed>>
     */
    private const BLOCK_DEFAULTS = [
        'hero' => [
            'subtitle'  => 'WELCOME TO NOVI-AGRO',
            'headline'  => 'Advanced Animal Care Solutions',
            'cta_label' => 'Browse Products',
            'cta_url'   => '/products',
            'video_src' => '/assets/videos/Nigerian_Breed_Cow_Video_Generated.mp4',
        ],
        'feature-grid' => [
            'card_1_title' => 'Quality Services',
            'card_1_text'  => 'Comprehensive livestock care services.',
            'card_1_icon'  => 'fas fa-hand-holding-heart',
            'card_2_title' => 'Professional Team',
            'card_2_text'  => 'Experienced team of veterinarians and livestock specialists.',
            'card_2_icon'  => 'fas fa-user-md',
            'card_3_title' => 'Livestock Feeding Award',
            'card_3_text'  => 'Award-winning livestock feeds for optimal growth and health.',
            'card_3_icon'  => 'fas fa-award',
            'card_4_title' => 'Quality Products',
            'card_4_text'  => 'Comprehensive quality feeds for livestock growth.',
            'card_4_icon'  => 'fas fa-seedling',
        ],
        'about-intro' => [
            'tagline'       => 'Novi Agro Nig. Ltd',
            'title'         => 'Brings your farming business to life',
            'paragraph_1'   => 'At Novi Agro Nig. Ltd, we are committed to revolutionizing the agricultural landscape in Nigeria. With a focus on sustainable practices and innovation, we provide high-quality livestock feed additives and expert consultancy services to empower farmers and enhance productivity.',
            'quality_title' => 'Quality <br> farming services',
            'stat_value'    => '2K',
            'stat_label'    => 'Satisfied customers',
            'paragraph_2'   => 'We offer a comprehensive range of high-quality livestock feeds additives tailored to meet the specific nutritional needs of various animals, including poultry, goats, and cattle. Our products are formulated with premium ingredients to ensure optimal growth, health, and productivity for your farm.',
            'bullet_1'      => 'Feed Additives at its best',
            'bullet_2'      => 'Professional consultancy service for all your farming business',
            'bullet_3'      => 'There are many variations of products of Novi Agro available',
            'image_main'    => '/assets/images/generated/about_main.png',
            'image_thumb'   => '/assets/images/generated/about_thumbnail.png',
        ],
        'species-cards' => [
            'heading'      => 'SPECIES',
            'intro'        => 'We provide premium feed additives and nutritional solutions tailored for each animal category.',
            'card_1_label' => 'Poultry',
            'card_1_image' => '/assets/images/backgrounds/hens-factory-chicken-cages.jpg',
            'card_2_label' => 'Cattle',
            'card_2_image' => '/assets/images/backgrounds/photorealistic-view-cow-grazing-outdoors.jpg',
            'card_3_label' => 'Pigs',
            'card_3_image' => '/assets/images/backgrounds/selective-closeup-shot-pink-pigs-barn.jpg',
        ],
        'services-summary' => [
            'tagline'          => 'Our Services',
            'title'            => 'The services we offer to <br> our clients',
            'background_image' => '/assets/images/generated/services_bg.png',
            'card_1_title'     => 'Livestock Feeds',
            'card_1_icon'      => 'icon-grass',
            'card_1_text'      => 'Nutrient-rich additives for optimal livestock health.',
            'card_2_title'     => 'Consultancy',
            'card_2_icon'      => 'icon-group',
            'card_2_text'      => 'Expert guidance on farm and livestock management.',
            'card_3_title'     => 'Customer Support',
            'card_3_icon'      => 'icon-telephone-call',
            'card_3_text'      => 'Reliable support for inquiries and after-sales.',
        ],
        'work-process' => [
            'tagline'      => 'Our work process',
            'title'        => 'See how we complete the work',
            'paragraph'    => 'At Novi Agro, we follow a systematic and efficient approach to ensure your farm gets the best nutrition, supplies, and professional guidance for maximum productivity.',
            'step_1_icon'  => 'icon-high-quality',
            'step_1_title' => 'Livestock Nutrition',
            'step_1_text'  => 'Premium feed additives crafted to boost animal health and productivity across your farm.',
            'step_2_icon'  => 'icon-shop-bag',
            'step_2_title' => 'Product Supply',
            'step_2_text'  => 'Providing a consistent supply of essential agro-products and supplements for your farm.',
            'step_3_icon'  => 'icon-group',
            'step_3_title' => 'Consultancy',
            'step_3_text'  => 'Expert advisory services to help you scale your farming business with modern techniques.',
            'step_4_icon'  => 'icon-location',
            'step_4_title' => 'Logistics',
            'step_4_text'  => 'Fast reliable delivery, ensuring your supplies arrive when you need them.',
        ],
        'benefits' => [
            'tagline'      => 'Your BENEFITS',
            'title'        => 'Why is mine different from others?',
            'paragraph_1'  => "At Novi-Agro, we stand out through our unwavering commitment to quality, sustainability, and farmer empowerment. We don't just supply products, we build lasting partnerships that help your farm thrive.",
            'card_1_title' => 'Quality <br> services',
            'card_2_title' => 'Skilled <br> Team',
            'paragraph_2'  => 'We provide end-to-end support, from consultation to providing feeds additive suitable for your livestock, ensuring you get the most out of every season.',
            'bullet_1'     => 'Expert team members',
            'bullet_2'     => 'Affordable quality services',
            'bullet_3'     => 'Professional Farming Services',
            'cta_label'    => 'Find out more',
            'image_main'   => '/assets/images/generated/benefits_hero.png',
            'image_thumb'  => '/assets/images/generated/benefits_thumb.png',
        ],
        'stats-bar' => [
            'tagline'       => 'OUR FUN FACTS',
            'title'         => 'The grass is always <br> greener on our side',
            'paragraph'     => "We are committed to provide innovative agricultural solutions that enhance animal nutrition, boost farmers' productivity, and promote sustainable food security across Nigeria and beyond.",
            'cta_label'     => 'Hit us up',
            'cta_text'      => 'Let us know how we can help You!',
            'stat_1_value'  => '10',
            'stat_1_suffix' => 'K',
            'stat_1_title'  => 'Projects Completed',
            'stat_1_text'   => 'Successful agricultural projects delivered',
            'stat_2_value'  => '2110',
            'stat_2_title'  => 'Satisfied Customers',
            'stat_2_text'   => 'Trusted by farmers and stakeholders',
        ],
        'about-detail' => [
            'tagline'        => 'Novi-Agro Limited',
            'title'          => 'We offer expert livestock solutions',
            'paragraph'      => 'At Novi Agro, we are dedicated to improving livestock health and farm productivity. We provide high-quality feed additives and professional veterinary consultancy to ensure your animals thrive and your farm operations run smoothly.',
            'quality_card_1' => 'Quality <br> livestock services',
            'quality_card_2' => "We're experienced <br> specialists",
            'stat_value'     => '5+',
            'stat_label'     => 'Years Of Experience',
            'bullet_1'       => 'Premium feed additives and nutritional supplements',
            'bullet_2'       => 'Expert veterinary and farming consultancy',
            'bullet_3'       => 'Commitment to maximizing farm productivity',
            'cta_label'      => 'Learn More',
            'image_main'     => '/assets/images/about12_1.jpg',
            'image_thumb'    => '/assets/images/About_2.jpg',
        ],
        'feature-grid-about' => [
            'card_bg'      => '/assets/images/shapes/feature-bg-2-1.png',
            'card_1_title' => 'Quality services',
            'card_1_text'  => 'Premium livestock feed additives and nutrition solutions backed by science and field-tested results.',
            'card_1_icon'  => 'icon-guaranteed',
            'card_2_title' => 'Professional team',
            'card_2_text'  => "Experienced veterinarians and livestock specialists dedicated to optimizing your farm's productivity.",
            'card_2_icon'  => 'icon-gardening-1',
            'card_3_title' => 'Guaranteed results',
            'card_3_text'  => 'Proven formulations that deliver measurable improvements in livestock health, growth, and yield.',
            'card_3_icon'  => 'icon-guarantee',
            'card_4_title' => 'Happy customers',
            'card_4_text'  => 'Trusted by over 2,000 farmers across Nigeria for reliable products and responsive after-sales support.',
            'card_4_icon'  => 'icon-rating',
        ],
        'benefits-about' => [
            'tagline'      => "We're Skilled Full",
            'title'        => 'Our expertise in <br> livestock solutions',
            'paragraph'    => 'Drawing on decades of agricultural experience, we formulate science-backed nutritional solutions for Pigs, Poultry, and Cattle. Our commitment ensures that herds and flocks maintain peak health and unrivaled productivity.',
            'card_1_title' => 'Quality services',
            'card_1_text'  => 'Delivering measurable improvements in animal health and farm efficiency.',
            'card_2_title' => 'Skilled Team',
            'card_2_text'  => 'Delivering measurable improvements in animal health and farm efficiency.',
            'bullet_1'     => 'Expert team members',
            'bullet_2'     => 'Affordable quality services',
            'bullet_3'     => 'Professional Livestock Services',
            'bullet_4'     => 'Trusted agricultural partners',
            'stat_value'   => '1025+',
            'stat_label'   => 'Clients Reviews',
            'image'        => '/assets/images/About_3.jpg',
        ],
        'journey-growth' => [
            'title'       => 'Our Journey',
            'paragraph'   => "Novi-Agro began its journey in 2022 with an initial workforce of 15 employees. The company has since recorded steady expansion, increasing its staff strength to 30 in 2023, 55 in 2024, and 70 in 2025. By 2026, the organization's team has reached 100 personnel, reflecting its continuous growth trajectory.",
            'card_label'  => 'Staff Members',
            'card_1_year' => '2022',
            'card_1_value' => '15',
            'card_2_year' => '2023',
            'card_2_value' => '30',
            'card_3_year' => '2024',
            'card_3_value' => '55',
            'card_4_year' => '2025',
            'card_4_value' => '70',
            'card_5_year' => '2026',
            'card_5_value' => '100',
        ],
        'customer-growth' => [
            'eyebrow'         => 'Growth Metrics',
            'title'           => 'Customer Growth',
            'paragraph'       => 'Since its establishment in 2022, Novi-Agro has built a strong and dedicated customer community, with many clients returning regularly for our products. Over time, we have experienced consistent growth in our clientele, reflecting increasing trust and demand.',
            'bar_1_year'      => '2022',
            'bar_1_width'     => '10%',
            'bar_1_label'     => '120',
            'bar_2_year'      => '2023',
            'bar_2_width'     => '28%',
            'bar_2_label'     => '250',
            'bar_3_year'      => '2024',
            'bar_3_width'     => '46%',
            'bar_3_label'     => '400',
            'bar_4_year'      => '2025',
            'bar_4_width'     => '72%',
            'bar_4_label'     => '800',
            'bar_5_year'      => '2026',
            'bar_5_width'     => '96%',
            'bar_5_label'     => 'Over 2,000 ✦',
            'summary_1_value' => '2,000',
            'summary_1_label' => 'Total Customers',
            'summary_2_value' => '17',
            'summary_2_label' => 'Growth Since 2022',
            'summary_3_value' => '4',
            'summary_3_label' => 'Years of Operation',
            'summary_4_value' => '100',
            'summary_4_label' => 'Year-on-Year Growth',
        ],
        'product-catalog' => [
            'search_placeholder'  => 'Search for available products',
            'sort_default_label'  => 'Sort by Default',
            'sort_newest_label'   => 'Sort by Newest',
            'sort_alpha_label'    => 'Sort by Alphabetical (Z-A)',
            'sort_popular_label'  => 'Sort by Popularity',
            'filter_type_title'   => 'Product Type',
            'filter_animal_title' => 'Animal Category',
            'cat_all_label'       => 'All Products',
            'cat_cattle_label'    => 'Cattle',
            'cat_pigs_label'      => 'Swine/Pigs',
            'cat_poultry_label'   => 'Poultry',
        ],
        'service-cards-grid' => [
            'card_1_title' => 'Livestock Additives',
            'card_1_text'  => 'High-quality, nutritionally balanced feeds additives designed to enhance the growth and health of your livestock.',
            'card_1_cta'   => 'Enquire Now',
            'card_1_icon'  => 'icon-grass',
            'card_1_image' => '/assets/images/generated/service_additives.png',
            'card_2_title' => 'Consultancy',
            'card_2_text'  => 'Expert guidance on farm management, animal health, and sustainable agricultural practices.',
            'card_2_cta'   => 'Enquire Now',
            'card_2_icon'  => 'icon-group',
            'card_2_image' => '/assets/images/generated/booking_vet1.png',
            'card_3_title' => 'Animal Care',
            'card_3_text'  => 'Comprehensive range of animal health products and equipment for professional care.',
            'card_3_cta'   => 'Enquire Now',
            'card_3_icon'  => 'fas fa-stethoscope',
            'card_3_image' => '/assets/images/generated/gallery_support.png',
            'card_4_title' => 'Farm Support',
            'card_4_text'  => 'Reliable assistance with day-to-day farm operations and infrastructure maintenance.',
            'card_4_cta'   => 'Discover More',
            'card_4_icon'  => 'fas fa-hands-helping',
            'card_4_image' => '/assets/images/generated/gallery_pasture.png',
            'card_5_title' => 'Training',
            'card_5_text'  => 'Empowering farmers with modern techniques and knowledge for better productivity.',
            'card_5_cta'   => 'Discover More',
            'card_5_icon'  => 'fas fa-graduation-cap',
            'card_5_image' => '/assets/images/generated/gallery_poultry.png',
            'card_6_title' => 'Export Services',
            'card_6_text'  => 'Facilitating the global trade of quality livestock and agricultural products.',
            'card_6_cta'   => 'Enquire Now',
            'card_6_icon'  => 'fas fa-shipping-fast',
            'card_6_image' => '/assets/images/generated/services_bg.png',
        ],
        'contact-info-cards' => [
            'title'          => 'Do you have questions? Speak<br> with us through message',
            'subtitle'       => 'You can also reach out to us by phone or email for any enquiries',
            'background_image' => '/assets/images/shapes/contact-bg-1.png',
            'card_1_icon'    => 'icon-location',
            'card_1_title'   => 'Our office',
            'card_1_text'    => 'KM 10, Old Lagos-Ibadan Expressway, New Garage, Ibadan',
            'card_2_icon'    => 'icon-telephone-call',
            'card_2_title'   => 'Make a call',
            'card_2_phone_1' => '+2347041041756',
            'card_2_phone_2' => '+2349012000101',
            'card_3_icon'    => 'icon-help',
            'card_3_title'   => 'Support',
            'card_3_email'   => 'info@novi-agro.com',
        ],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->pages as $def) {
                if ($def['is_homepage']) {
                    Page::where('is_homepage', true)
                        ->where('slug', '!=', $def['slug'])
                        ->update(['is_homepage' => false]);
                }

                $page = Page::updateOrCreate(
                    ['slug' => $def['slug']],
                    [
                        'title'        => $def['title'],
                        'layout'       => $def['layout'],
                        'status'       => 'published',
                        'published_at' => now(),
                        'is_homepage'  => $def['is_homepage'],
                    ]
                );

                foreach ($def['blocks'] as $i => $blockType) {
                    $block = PageBlock::firstOrNew([
                        'page_id' => $page->id,
                        'type'    => $blockType,
                    ]);

                    if (! $block->exists) {
                        $block->order_column = $i;
                        $block->is_visible = true;
                    }

                    // Backfill default content only when the block has no data yet,
                    // so admin edits are never overwritten on re-seed.
                    if (empty($block->data)) {
                        $block->data = $this->defaultDataFor($blockType);
                    }

                    $block->save();
                }
            }
        });
    }

    /**
     * Resolve seed data for a block type, copying any image defaults onto the
     * public disk so the Filament FileUpload fields display the current image.
     *
     * @return array<string, mixed>|null
     */
    private function defaultDataFor(string $type): ?array
    {
        $data = self::BLOCK_DEFAULTS[$type] ?? null;
        if ($data === null) {
            return null;
        }

        foreach (self::IMAGE_KEYS[$type] ?? [] as $key) {
            if (! empty($data[$key])) {
                $data[$key] = $this->storeAssetOnPublicDisk($data[$key], $type);
            }
        }

        return $data;
    }

    /**
     * Copy a bundled /assets image onto the public disk under blocks/<type>/ and
     * return the disk-relative path Filament FileUpload expects. Idempotent; if
     * the source is missing, the original path is kept (blockAsset passes it
     * through so the site still renders).
     */
    private function storeAssetOnPublicDisk(string $assetPath, string $type): string
    {
        $source = public_path(ltrim($assetPath, '/'));
        if (! is_file($source)) {
            return $assetPath;
        }

        $target = 'blocks/' . $type . '/' . basename($source);
        $disk = Storage::disk('public');
        if (! $disk->exists($target)) {
            $disk->put($target, file_get_contents($source));
        }

        return $target;
    }
}
