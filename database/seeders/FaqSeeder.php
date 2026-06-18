<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = json_decode(
            file_get_contents(database_path('seeders/data/faqs.json')),
            associative: true,
        );

        foreach ($faqs as $row) {
            Faq::updateOrCreate(
                ['question' => $row['question']],
                [
                    'answer'       => $row['answer'],
                    'order_column' => $row['order'],
                    'is_published' => true,
                ],
            );
        }
    }
}
