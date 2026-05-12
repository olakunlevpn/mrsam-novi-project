<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

                $page->blocks()->delete();

                foreach ($def['blocks'] as $i => $blockType) {
                    $data = null;

                    if ($def['slug'] === 'home' && $blockType === 'hero') {
                        $data = [
                            'subtitle'  => 'WELCOME TO NOVI-AGRO',
                            'headline'  => 'Advanced Animal Care Solutions',
                            'cta_label' => 'Browse Products',
                            'cta_url'   => '/products',
                            'video_src' => '/assets/videos/Nigerian_Breed_Cow_Video_Generated.mp4',
                        ];
                    }

                    PageBlock::create([
                        'page_id'      => $page->id,
                        'type'         => $blockType,
                        'data'         => $data,
                        'order_column' => $i,
                        'is_visible'   => true,
                    ]);
                }
            }
        });
    }
}
