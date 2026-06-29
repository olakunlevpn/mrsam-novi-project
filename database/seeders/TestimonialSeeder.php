<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = json_decode(
            file_get_contents(database_path('seeders/data/testimonials.json')),
            associative: true,
        );

        foreach ($testimonials as $row) {
            Testimonial::updateOrCreate(
                ['content' => $row['content']],
                [
                    'name'         => $row['name'],
                    'designation'  => $row['designation'],
                    'image'        => $row['image'],
                    'rating'       => $row['rating'],
                    'order_column' => $row['order'],
                    'is_published' => true,
                ],
            );
        }
    }
}
