<?php

namespace Tests\Feature\Models;

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope(): void
    {
        Faq::create(['question' => 'Q1', 'answer' => 'A1', 'is_published' => true]);
        Faq::create(['question' => 'Q2', 'answer' => 'A2', 'is_published' => true]);
        Faq::create(['question' => 'Q3', 'answer' => 'A3', 'is_published' => false]);

        $this->assertSame(2, Faq::published()->count());
    }
}
