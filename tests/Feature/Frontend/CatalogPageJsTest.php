<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class CatalogPageJsTest extends TestCase
{
    public function test_product_service_calls_the_api_endpoint(): void
    {
        $contents = file_get_contents(public_path('assets/js/services/product.service.js'));
        $this->assertStringContainsString('/api/products', $contents);
        $this->assertStringNotContainsString('productsData', $contents);
    }

    public function test_main_js_awaits_async_fetch(): void
    {
        $contents = file_get_contents(public_path('assets/js/main.js'));
        // The subscribe callback should be async-aware.
        $this->assertMatchesRegularExpression('/AppState\.subscribe\s*\(\s*async/', $contents);
    }
}
