<?php

namespace Tests\Feature\Filament;

use App\Filament\Clusters\Settings\Pages\ManageBranding;
use App\Filament\Clusters\Settings\Pages\ManageComments;
use App\Filament\Clusters\Settings\Pages\ManageContactInfo;
use App\Filament\Clusters\Settings\Pages\ManageFooter;
use App\Filament\Clusters\Settings\Pages\ManageMail;
use App\Filament\Clusters\Settings\Pages\ManageSeo;
use App\Filament\Clusters\Settings\Pages\ManageSiteIdentity;
use App\Filament\Clusters\Settings\Pages\ManageSocialLinks;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SettingsClusterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        Storage::fake('public');
    }

    public static function clusterPageProvider(): array
    {
        return [
            'branding' => [ManageBranding::class],
            'contact'  => [ManageContactInfo::class],
            'social'   => [ManageSocialLinks::class],
            'site'     => [ManageSiteIdentity::class],
            'footer'   => [ManageFooter::class],
            'seo'      => [ManageSeo::class],
            'comments' => [ManageComments::class],
            'mail'     => [ManageMail::class],
        ];
    }

    #[DataProvider('clusterPageProvider')]
    public function test_admin_can_open_each_cluster_page(string $page): void
    {
        $this->actingAs($this->admin)
            ->get($page::getUrl())
            ->assertOk();
    }

    #[DataProvider('clusterPageProvider')]
    public function test_non_admin_forbidden(string $page): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get($page::getUrl())
            ->assertForbidden();
    }

    public function test_branding_page_persists_text_fields(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ManageBranding::class)
            ->fillForm([
                'brand__name'    => 'Acme Feeds',
                'brand__tagline' => 'Better feed, better farm',
            ])
            ->call('save')
            ->assertNotified();

        $this->assertSame('Acme Feeds',            Setting::get('brand.name'));
        $this->assertSame('Better feed, better farm', Setting::get('brand.tagline'));
    }

    public function test_comments_page_persists_moderation_toggle(): void
    {
        Setting::set('comments.moderation', true, 'comments');

        Livewire::actingAs($this->admin)
            ->test(ManageComments::class)
            ->fillForm(['comments__moderation' => false])
            ->call('save')
            ->assertNotified();

        $this->assertFalse((bool) Setting::get('comments.moderation'));
    }

    public function test_moderation_toggle_shows_default_on_when_unset(): void
    {
        Setting::query()->where('key', 'comments.moderation')->delete();

        Livewire::actingAs($this->admin)
            ->test(ManageComments::class)
            ->assertSet('data.comments__moderation', true);
    }

    public function test_branding_page_accepts_logo_and_favicon_uploads(): void
    {
        $logo    = UploadedFile::fake()->image('logo.png',  240, 80);
        $favicon = UploadedFile::fake()->image('favicon.png', 32, 32);

        Livewire::actingAs($this->admin)
            ->test(ManageBranding::class)
            ->fillForm([
                'brand__name'    => 'Acme',
                'brand__tagline' => 'Tag',
                'brand__logo'    => $logo,
                'brand__favicon' => $favicon,
            ])
            ->call('save')
            ->assertNotified();

        $logoPath    = Setting::get('brand.logo');
        $faviconPath = Setting::get('brand.favicon');

        $this->assertIsString($logoPath);
        $this->assertIsString($faviconPath);
        $this->assertStringStartsWith('branding/logo/',     $logoPath);
        $this->assertStringStartsWith('branding/favicons/', $faviconPath);
        $this->assertTrue(Storage::disk('public')->exists($logoPath));
        $this->assertTrue(Storage::disk('public')->exists($faviconPath));
    }

    public function test_seo_page_persists_robots_txt(): void
    {
        $robots = "User-agent: *\nDisallow: /admin\nSitemap: https://novi-agro.com/sitemap.xml";

        Livewire::actingAs($this->admin)
            ->test(ManageSeo::class)
            ->fillForm(['seo__robots_txt' => $robots])
            ->call('save')
            ->assertNotified();

        $this->assertSame($robots, Setting::get('seo.robots_txt'));
    }
}
