<?php

namespace Tests\Feature\Models;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_category_with_auto_slug(): void
    {
        $category = FaqCategory::create(['name' => 'General Questions']);

        $this->assertSame('general-questions', $category->slug);
    }

    public function test_belongs_to_category_nullable(): void
    {
        $faq = Faq::create([
            'question'     => 'What is Novi Agro?',
            'answer'       => 'An agro company.',
            'is_published' => true,
        ]);

        $this->assertNull($faq->faq_category_id);
        $this->assertNull($faq->category);
    }

    public function test_published_scope(): void
    {
        $category = FaqCategory::create(['name' => 'General']);

        Faq::create(['faq_category_id' => $category->id, 'question' => 'Q1', 'answer' => 'A1', 'is_published' => true]);
        Faq::create(['faq_category_id' => $category->id, 'question' => 'Q2', 'answer' => 'A2', 'is_published' => true]);
        Faq::create(['faq_category_id' => $category->id, 'question' => 'Q3', 'answer' => 'A3', 'is_published' => false]);

        $this->assertSame(2, Faq::published()->count());
    }
}
