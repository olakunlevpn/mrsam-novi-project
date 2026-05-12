<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['brand.name',             'Novi Agro',                                              'brand'],
            ['brand.tagline',          'Quality Feed - Healthy Life',                            'brand'],
            ['brand.logo',             '/assets/images/images-removebg-preview.png',             'brand'],
            ['contact.email',          'info@novi-agro.com',                                     'contact'],
            ['contact.phone',          '+2347041041756',                                         'contact'],
            ['contact.address',        'New Garage, Ibadan.',                                    'contact'],
            ['social.facebook',        'https://www.facebook.com/profile.php?id=100077163775495','social'],
            ['social.instagram',       'https://www.instagram.com/novi_agroltd/',                'social'],
            ['site.title_suffix',      '| Quality Feeds - Healthy Life',                         'site'],
            ['footer.categories_title','Categories',                                             'footer'],
            ['footer.gallery_title',   'Gallery',                                                'footer'],
            ['footer.products_title',  'Products',                                               'footer'],
            ['footer.gallery_images',  self::defaultGalleryImages(),                             'footer'],
        ];

        foreach ($settings as [$key, $value, $group]) {
            Setting::set($key, $value, $group);
        }
    }

    /**
     * Seed the original hardcoded gallery thumbnails so the footer widget
     * renders out-of-the-box on a fresh install. Admin can edit or clear the
     * list from the Site Settings page.
     *
     * @return array<int, array{src: string, alt: string}>
     */
    private static function defaultGalleryImages(): array
    {
        return [
            ['src' => '/assets/images/generated/gallery_cattle.png',  'alt' => 'Quality Cattle'],
            ['src' => '/assets/images/generated/gallery_poultry.png', 'alt' => 'Modern Poultry'],
            ['src' => '/assets/images/generated/gallery_pigs.png',    'alt' => 'Swine Care'],
            ['src' => '/assets/images/generated/gallery_feed.png',    'alt' => 'Premium Feed'],
            ['src' => '/assets/images/generated/gallery_support.png', 'alt' => 'Expert Support'],
            ['src' => '/assets/images/generated/gallery_pasture.png', 'alt' => 'Sustainable Pasture'],
        ];
    }
}
