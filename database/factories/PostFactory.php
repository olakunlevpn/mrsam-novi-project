<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'          => fake()->unique()->sentence(),
            'excerpt'        => fake()->paragraph(),
            'body'           => fake()->paragraphs(3, true),
            'author_id'      => User::factory(),
            'status'         => 'draft',
            'published_at'   => null,
            'allow_comments' => true,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => 'published',
            'published_at' => now(),
        ]);
    }
}
