<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('image')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('order_column');
            $table->index('is_published');
        });

        // Seed the reviews that previously lived as hard-coded markup so prod
        // (which runs migrate --force but not db:seed) keeps the existing
        // section. Guarded: skip if the table already holds rows.
        if (DB::table('testimonials')->count() === 0) {
            $now = now();
            $rows = [];
            foreach ($this->seedReviews() as $i => $review) {
                $rows[] = array_merge($review, [
                    'rating'       => 5,
                    'order_column' => $i,
                    'is_published' => true,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
            DB::table('testimonials')->insert($rows);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }

    /**
     * @return array<int, array{name: string, designation: string, image: string, content: string}>
     */
    private function seedReviews(): array
    {
        return [
            [
                'name'        => 'Tymoshenko',
                'designation' => 'Chairman',
                'image'       => '/assets/images/resources/Chairman1.jpg',
                'content'     => "Novi Agro's feed additives have been a game-changer for our livestock. We've seen vastly improved health metrics and much higher yields since incorporating their solutions into our nutritional programs.",
            ],
            [
                'name'        => 'Kolawole Ishola',
                'designation' => 'C.E.O',
                'image'       => '/assets/images/resources/ishola.jpg',
                'content'     => "Partnering with Novi Agro has significantly enhanced our production efficiency. Their high-quality additives and expert guidance have been instrumental in our farm's success and sustainability across Nigeria.",
            ],
            [
                'name'        => 'Dr. Bayo',
                'designation' => 'Nutritionist',
                'image'       => '/assets/images/resources/DR Bayo.jpeg',
                'content'     => "As a nutritionist, I am impressed by the precision of Novi Agro's formulations. They provide essential bio-available nutrients that are often missing from standard feeds, ensuring optimal animal growth and vitality.",
            ],
            [
                'name'        => 'Goblin Lion',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/gardeners-1-1.png',
                'content'     => "The transformation in our poultry production since adopting Novi Agro's solutions has been remarkable. We are recording faster growth rates and much better overall health in our flocks, which has boosted our market value.",
            ],
            [
                'name'        => 'Alen Martin',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/chairman.png',
                'content'     => "Consistency is key in large-scale farming, and that's exactly what Novi Agro delivers. Their products are reliable, effective, and backed by excellent technical support which is rare in the agricultural supply industry.",
            ],
            [
                'name'        => 'Goblin Lion',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/gardeners-1-1.png',
                'content'     => "The professionalism and dedication of the Novi Agro team are unmatched. Their innovative approach to livestock nutrition has helped us overcome major production challenges effortlessly while reducing our overhead costs.",
            ],
            [
                'name'        => 'Man Hanson',
                'designation' => 'Founder',
                'image'       => '/assets/images/resources/ishola.jpg',
                'content'     => "We've tried various additives over the years, but none have matched the results we get from Novi Agro. Our livestock are healthier, grow faster, and our profit margins on the farm have seen a significant positive uptick.",
            ],
        ];
    }
};
