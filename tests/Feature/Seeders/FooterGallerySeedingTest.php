<?php

namespace Tests\Feature\Seeders;

use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FooterGallerySeedingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_seeder_copies_gallery_files_onto_public_disk(): void
    {
        $this->seed(SettingSeeder::class);

        $images = Setting::get('footer.gallery_images');
        $this->assertIsArray($images);
        $this->assertNotEmpty($images);

        foreach ($images as $entry) {
            $this->assertIsArray($entry);
            $this->assertArrayHasKey('src', $entry);
            $this->assertArrayHasKey('alt', $entry);
            $this->assertStringStartsWith('footer/gallery/', $entry['src']);
            $this->assertTrue(
                Storage::disk('public')->exists($entry['src']),
                "Gallery file {$entry['src']} not on the public disk",
            );
        }
    }

    public function test_reseeding_is_idempotent_and_keeps_existing_files(): void
    {
        $this->seed(SettingSeeder::class);
        $firstSnapshot = Setting::get('footer.gallery_images');

        $this->seed(SettingSeeder::class);
        $secondSnapshot = Setting::get('footer.gallery_images');

        $this->assertSame($firstSnapshot, $secondSnapshot);

        foreach ($secondSnapshot as $entry) {
            $this->assertTrue(Storage::disk('public')->exists($entry['src']));
        }
    }

    public function test_footer_blade_resolves_relative_src_via_storage_url(): void
    {
        $this->seed(SettingSeeder::class);

        $response = $this->get('/');
        $response->assertOk();

        $images = Setting::get('footer.gallery_images');
        $this->assertNotEmpty($images);

        $firstRelative = $images[0]['src'];
        $expectedUrl   = Storage::disk('public')->url($firstRelative);
        $response->assertSee($expectedUrl, escape: false);
    }
}
