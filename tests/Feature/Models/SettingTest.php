<?php

namespace Tests\Feature\Models;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_default_when_missing(): void
    {
        $result = Setting::get('nope', 'fallback');

        $this->assertSame('fallback', $result);
    }

    public function test_set_and_get_roundtrip_string_and_array(): void
    {
        Setting::set('brand.name', 'Novi Agro');

        $this->assertSame('Novi Agro', Setting::get('brand.name'));

        Setting::set('contact.address', ['line1' => 'X']);

        $address = Setting::get('contact.address');
        $this->assertIsArray($address);
        $this->assertArrayHasKey('line1', $address);
    }

    public function test_set_is_idempotent(): void
    {
        Setting::set('brand.name', 'First');
        Setting::set('brand.name', 'Second');

        $this->assertSame(1, Setting::where('key', 'brand.name')->count());
        $this->assertSame('Second', Setting::get('brand.name'));
    }

    public function test_group_returns_only_matching(): void
    {
        Setting::set('brand.name', 'Novi Agro', 'brand');
        Setting::set('brand.tagline', 'Tagline', 'brand');
        Setting::set('social.twitter', '@novi', 'social');

        $this->assertSame(2, Setting::group('brand')->count());
        $this->assertSame(1, Setting::group('social')->count());
    }
}
