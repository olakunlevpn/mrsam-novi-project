<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingSeeder extends Seeder
{
    /**
     * Footer gallery files to copy into the public disk, ordered by the
     * sequence they should appear in the widget. Each tuple is
     * [source path under public/, alt text]. Files are copied to
     * storage/app/public/footer/gallery/<basename> so the Settings page
     * sees them as proper FileUpload values, indistinguishable from a
     * real admin upload.
     *
     * @var array<int, array{0: string, 1: string}>
     */
    private const GALLERY_SOURCES = [
        ['assets/images/generated/gallery_cattle.png',  'Quality Cattle'],
        ['assets/images/generated/gallery_poultry.png', 'Modern Poultry'],
        ['assets/images/generated/gallery_pigs.png',    'Swine Care'],
        ['assets/images/generated/gallery_feed.png',    'Premium Feed'],
        ['assets/images/generated/gallery_support.png', 'Expert Support'],
        ['assets/images/generated/gallery_pasture.png', 'Sustainable Pasture'],
    ];

    private const GALLERY_DESTINATION = 'footer/gallery';

    private const LOGO_SOURCE      = 'assets/images/images-removebg-preview.png';
    private const LOGO_DESTINATION = 'branding/logo';

    private const FAVICON_SOURCE      = 'assets/images/favicons/favicon_io/favicon-32x32.png';
    private const FAVICON_DESTINATION = 'branding/favicons';

    public function run(): void
    {
        $settings = [
            ['brand.name',             'Novi Agro',                                              'brand'],
            ['brand.tagline',          'Quality Feed - Healthy Life',                            'brand'],
            ['brand.logo',             $this->prepareBrandAsset(self::LOGO_SOURCE,    self::LOGO_DESTINATION),    'brand'],
            ['brand.favicon',          $this->prepareBrandAsset(self::FAVICON_SOURCE, self::FAVICON_DESTINATION), 'brand'],
            ['contact.email',          'info@novi-agro.com',                                     'contact'],
            ['contact.phone',          '+2347041041756',                                         'contact'],
            ['contact.address',        'New Garage, Ibadan.',                                    'contact'],
            ['social.facebook',        'https://www.facebook.com/profile.php?id=100077163775495','social'],
            ['social.instagram',       'https://www.instagram.com/novi_agroltd/',                'social'],
            ['site.title_suffix',      '| Quality Feeds - Healthy Life',                         'site'],
            ['footer.categories_title','Categories',                                             'footer'],
            ['footer.gallery_title',   'Gallery',                                                'footer'],
            ['footer.products_title',  'Products',                                               'footer'],
            ['footer.gallery_images',  $this->prepareGalleryImages(),                            'footer'],
        ];

        foreach ($settings as [$key, $value, $group]) {
            Setting::set($key, $value, $group);
        }
    }

    /**
     * Copy a single branding asset (logo, favicon, etc.) onto the public
     * disk so the saved value matches what Filament's FileUpload writes.
     * Idempotent: if the destination already exists the path is returned
     * unchanged so re-seeds never duplicate files.
     */
    private function prepareBrandAsset(string $source, string $destinationDir): ?string
    {
        $absolute = public_path($source);
        $destPath = $destinationDir . '/' . basename($source);

        if (Storage::disk('public')->exists($destPath)) {
            return $destPath;
        }

        if (! File::exists($absolute)) {
            return null;
        }

        Storage::disk('public')->put($destPath, File::get($absolute));

        return $destPath;
    }

    /**
     * Copy each source thumbnail onto the public disk so the saved value
     * mirrors what Filament's FileUpload component writes when an admin
     * uploads a file. Idempotent: an existing destination file is reused
     * and re-seeding preserves the path the admin sees.
     *
     * @return array<int, array{src: string, alt: string}>
     */
    private function prepareGalleryImages(): array
    {
        $items = [];
        foreach (self::GALLERY_SOURCES as [$source, $alt]) {
            $absolute = public_path($source);
            $destPath = self::GALLERY_DESTINATION . '/' . basename($source);

            if (Storage::disk('public')->exists($destPath)) {
                $items[] = ['src' => $destPath, 'alt' => $alt];
                continue;
            }

            if (! File::exists($absolute)) {
                continue;
            }

            Storage::disk('public')->put($destPath, File::get($absolute));
            $items[] = ['src' => $destPath, 'alt' => $alt];
        }

        return $items;
    }
}
