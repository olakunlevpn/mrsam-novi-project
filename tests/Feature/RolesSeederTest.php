<?php

namespace Tests\Feature;

use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_three_canonical_roles(): void
    {
        $this->seed(RolesSeeder::class);

        foreach (['admin', 'editor', 'commenter'] as $name) {
            $this->assertTrue(Role::where('name', $name)->where('guard_name', 'web')->exists(), "Missing role: {$name}");
        }

        $this->assertSame(3, Role::count());
    }
}
