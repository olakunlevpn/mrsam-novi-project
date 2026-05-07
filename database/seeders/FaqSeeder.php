<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $category = FaqCategory::updateOrCreate(
            ['slug' => 'general'],
            ['name' => 'General', 'order_column' => 0],
        );

        $faqs = json_decode(
            file_get_contents(database_path('seeders/data/faqs.json')),
            associative: true,
        );

        foreach ($faqs as $row) {
            Faq::updateOrCreate(
                ['question' => $row['question']],
                [
                    'faq_category_id' => $category->id,
                    'answer'          => $row['answer'],
                    'order_column'    => $row['order'],
                    'is_published'    => true,
                ],
            );
        }
    }
}
